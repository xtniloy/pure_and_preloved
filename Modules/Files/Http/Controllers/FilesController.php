<?php

namespace Modules\Files\Http\Controllers;

use App\Enum\General;
use App\Http\Controllers\Controller;
use App\Jobs\DataPreProcessJob;
use App\Jobs\ProcessJsonFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Files\Models\Asset;
use Illuminate\Support\Facades\Cache;

class FilesController extends Controller
{
    public function index()
    {
        $files = Asset::orderBy('id','desc')->get();

        return view('files::index',compact('files'));


        //return response()->json(['message' => 'Files module working!']);
    }


    public function view(Asset $fileId)
    {
        $file = $fileId;

        $data_count = $file->data_count??0;

        $json = json_decode($file->jsons()->first()?->data);

        $progress = 0;
        if ($file->status === 'Pre-Processing'){
            $progress = $data_count ? intval(($file->jsons()->count()/$file->data_count)*100) : 0;
        }
        elseif ($file->status === 'Processing'){
            $progress = $data_count ? intval(($file->jsons()->where('status','=',1)->count()/$file->data_count)*100) : 0;
        }


        return view('admin.sections.uploader.view', [
            'fileId' => $file->id,
            'dataCount' => $file->data_count??0,
            'dataType' => $file->data_type??'Unknown', // 'precinct', 'voter', 'sub-division'
            'dataTitle' => $file->data_name,
            'fileName' => $file->original_name,
            'fileSize' => $file->file_size,
            'uploadedTime' => $file->upload_time,
            'status' => $file->status, // 'Uploaded', 'Pre-Processing', etc.
            'progress' => $progress,
            'jsonSample' => $json??"File Not processed yet" // First 5 records as sample
        ]);


    }

    public function uploadChunk(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'chunkIndex' => 'required|integer',
            'totalChunks' => 'required|integer',
            'fileName' => 'required|string',
            'uploadIdentifier' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

//        // Check if there are any unprocessed or in-progress jobs of DataPreProcessJob
        $jobExists = DB::table('jobs')
            ->where('payload', 'like', '%DataPreProcessJob%')
            ->exists();
        if ($jobExists) {
            sleep(15);
        }


        try {
            $uploadedChunk = $request->file('file');
            $chunkIndex = (int) $request->input('chunkIndex');
            $totalChunks = (int) $request->input('totalChunks');
            $fileName = $request->input('fileName');
            $uploadIdentifier = $request->input('uploadIdentifier');

            // Create temp directory for chunks
            $tempDir = storage_path('app/temp_uploads/' . $uploadIdentifier);
            if (!file_exists($tempDir)) {
                if (!mkdir($tempDir, 0755, true) && !is_dir($tempDir)) {
                    throw new \RuntimeException("Failed to create temp dir: {$tempDir}");
                }
            }

            // Save incoming chunk as chunk_{index}
            $savedName = 'chunk_' . $chunkIndex;
            $uploadedChunk->move($tempDir, $savedName);

            // Check how many chunks uploaded so far
            $uploadedChunks = glob($tempDir . '/chunk_*');
            $uploadedCount = $uploadedChunks === false ? 0 : count($uploadedChunks);

            // If not all chunks uploaded yet, return progress
            if ($uploadedCount < $totalChunks) {
                return response()->json([
                    'completed' => false,
                    'uploadedChunks' => $uploadedCount,
                    'totalChunks' => $totalChunks
                ]);
            }

            // All chunks present -> merge them into a .part file first
            $finalFileName = $this->sanitizeFileName($fileName);
            $finalPathDir = 'uploads/files/' . date('Y/m/d');
            $fullPathDir = storage_path('app/' . $finalPathDir);

            if (!file_exists($fullPathDir)) {
                if (!mkdir($fullPathDir, 0755, true) && !is_dir($fullPathDir)) {
                    throw new \RuntimeException("Failed to create final dir: {$fullPathDir}");
                }
            }

            $finalFilePath = $fullPathDir . '/' . $finalFileName;
            $finalTempPath = $finalFilePath . '.part';

            // Merge all chunk files in sequence
            $out = fopen($finalTempPath, 'wb');
            if ($out === false) {
                throw new \RuntimeException("Unable to open final temp file for writing: {$finalTempPath}");
            }

            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkFile = $tempDir . '/chunk_' . $i;
                if (!file_exists($chunkFile)) {
                    fclose($out);
                    // keep tempDir for debugging
                    return response()->json(['error' => "Missing chunk {$i}"], 500);
                }
                $in = fopen($chunkFile, 'rb');
                if ($in === false) {
                    fclose($out);
                    return response()->json(['error' => "Unable to open chunk {$i} for reading"], 500);
                }
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
            fclose($out);

            // Everything validated: atomically rename part -> final
            if (!rename($finalTempPath, $finalFilePath)) {
                // if rename fails, try copy+unlink
                if (!copy($finalTempPath, $finalFilePath)) {
                    return response()->json(['error' => 'Failed to store final file'], 500);
                }
                @unlink($finalTempPath);
            }
            $mimeType = File::mimeType($finalFilePath);

            // Save DB record
            $fileRecord = Asset::create([
                'original_name' => $fileName,
                'stored_name' => $finalFileName,
                'directory' => 'uploads',
                'path' => $finalPathDir . '/' . $finalFileName,

                'mime_type' => $mimeType,
                'status' => General::$files_status_code['Active'],
                'size' => filesize($finalFilePath),
            ]);

            // Clean up temp chunks AFTER DB write + validation (helps debug if something goes wrong)
            $this->deleteDirectory($tempDir);

            return response()->json([
                'completed' => true,
                'path' => $finalPathDir . '/' . $finalFileName,
                'fileId' => $fileRecord->id,
                'message' => 'File uploaded successfully'
            ]);
        } catch (\Throwable $e) {
            // keep temp files for debugging (optional) OR log full exception
            Log::error('Chunk upload failed: ' . $e->getMessage(), [
                'exception' => $e,
                'uploadIdentifier' => $request->input('uploadIdentifier', null),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadThumbnail(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fileId' => 'required|integer|exists:assets,id',
            'thumbnail' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $file = Asset::findOrFail($request->input('fileId'));
            $dataUrl = $request->input('thumbnail');

            if (!preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
                return response()->json(['error' => 'Invalid thumbnail data'], 422);
            }
            $imageType = strtolower($type[1]);
            $imageData = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));
            if ($imageData === false) {
                return response()->json(['error' => 'Base64 decode failed'], 422);
            }

            $thumbDir = 'uploads/files/thumbnails/' . date('Y/m/d');
            $fullThumbDir = storage_path('app/' . $thumbDir);
            if (!file_exists($fullThumbDir)) {
                if (!mkdir($fullThumbDir, 0755, true) && !is_dir($fullThumbDir)) {
                    throw new \RuntimeException("Failed to create thumbnail dir: {$fullThumbDir}");
                }
            }

            $baseName = pathinfo($file->stored_name, PATHINFO_FILENAME);
            $thumbName = $baseName . '_thumb_' . uniqid('', true) . '.' . $imageType;
            $thumbPath = $fullThumbDir . '/' . $thumbName;

            file_put_contents($thumbPath, $imageData);

            $file->thumbnail_path = $thumbDir . '/' . $thumbName;
            $file->save();

            return response()->json([
                'success' => true,
                'thumbnailUrl' => route('admin.file.thumbnail', ['fileId' => $file->id])
            ]);
        } catch (\Throwable $e) {
            Log::error('Thumbnail upload failed: ' . $e->getMessage(), [
                'exception' => $e,
                'fileId' => $request->input('fileId'),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function download($fileId)
    {
        $file = Asset::findOrFail($fileId);
        $filePath = storage_path('app/' . $file->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $file->original_name);
    }

    public function delete($fileId)
    {
        try {
            $file = Asset::findOrFail($fileId);
            $filePath = storage_path('app/' . $file->path);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file->delete();

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }

//    private function isValidJson($filePath)
//    {
//        ini_set('memory_limit', '2G');
//
//        try {
//            $content = file_get_contents($filePath);
//            $json = json_decode($content);
//            $validate = json_last_error() === JSON_ERROR_NONE;
//
//            if ($validate){
//                $this->data_name = $json->name;
//                $this->data_count = count($json->features);
//            }
//
//            return $validate;
//        } catch (\Throwable $e) {
//            return false;
//        }
//    }


    private function sanitizeFileName($fileName)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $cleanName = Str::slug($baseName);
        $uniqueName = $cleanName . '_' . uniqid('', true) . '.' . $extension;

        return $uniqueName;
    }

    public function uploaded_asset(string $stored_name)
    {
        $file = Cache::remember("asset_{$stored_name}", now()->addHours(6), function () use ($stored_name) {
            return Asset::where('stored_name', $stored_name)->first();
        });

        if (!$file) {
            abort(404);
        }

        $filePath = storage_path("app/{$file->path}");
        if (is_null($file->path) || !file_exists($filePath)) {
            abort(404);
        }

        $mimeType = $file->mime_type;

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
        ]);

    }

    public function thumbnail(int $fileId)
    {
        $file = Asset::findOrFail($fileId);
        if (!$file->thumbnail_path) {
            abort(404);
        }
        $thumbPath = storage_path('app/' . $file->thumbnail_path);
        if (!file_exists($thumbPath)) {
            abort(404);
        }
        return response()->file($thumbPath, [
            'Content-Type' => File::mimeType($thumbPath) ?: 'image/jpeg',
        ]);
    }


}
