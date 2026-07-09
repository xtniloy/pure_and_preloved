{{-- Reusable single-image picker slot.
     Expects: $prefix ('' for top-level fields, or e.g. "items[0]"), $imageId, $imageUrl --}}
@php
    $idName = $prefix === '' ? 'image_id' : $prefix . '[image_id]';
    $urlName = $prefix === '' ? 'image_url' : $prefix . '[image_url]';
    $previewSrc = null;
    if (!empty($imageId) && $imageId !== '__IDX__') {
        $previewSrc = route('admin.file.view', ['fileId' => $imageId]);
    } elseif (!empty($imageUrl)) {
        $previewSrc = asset($imageUrl);
    }
@endphp
<div class="image-slot">
    <div class="image-slot-preview d-flex flex-wrap gap-2 mb-2">
        @if($previewSrc)
            <div class="position-relative">
                <img src="{{ $previewSrc }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
        @endif
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary js-pick-image" type="button">Select Image</button>
        <button class="btn btn-sm btn-outline-danger js-clear-image" type="button">Remove</button>
    </div>
    <input type="hidden" class="js-image-id" name="{{ $idName }}" value="{{ $imageId === '__IDX__' ? '' : $imageId }}">
    <input type="hidden" class="js-image-url" name="{{ $urlName }}" value="{{ $imageUrl }}">
</div>
