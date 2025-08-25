@extends('email.layout.main')
@section('content')
    <tr>
        <td style="line-height: 24px; font-size: 15px; width: 100%; margin: 0; padding: 40px;" align="left" bgcolor="#ffffff">
            <h1 class="h3 fw-700" style="padding-top: 0; padding-bottom: 0; font-weight: 700 !important; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left">Thank you for registering with us!</h1>
            <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                <tbody>
                <tr>
                    <td style="line-height: 16px; font-size: 15px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                        &#160;
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="" style="line-height: 24px; font-size: 15px; width: 100%; margin: 0;" align="left">
                We are excited to have you join us in our community.
                Please confirm your registration to continue.<br><br>
                You will be asked to set a password to access your account.
            </p>
            <br>
            <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                <tbody>
                <tr>
                    <td style="line-height: 16px; font-size: 15px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                        &#160;
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="ax-center" role="presentation" align="center"  border="0" cellpadding="0" cellspacing="0" style="border-radius: 6px; border-collapse: separate !important; font-weight: 700 !important;">
                <tbody>
                <tr>
                    <td style="line-height: 24px; font-size: 15px; border-radius: 6px; font-weight: 700 !important; margin: 0;" align="center" bgcolor="#0d6efd">
                        <a href="{{ $details['link'] }}" style="color: #ffffff; font-size: 15px; font-family: Helvetica, Arial, sans-serif; text-decoration: none; border-radius: 6px; line-height: 20px; display: block; font-weight: 700 !important; white-space: nowrap; background-color: #0d6efd; padding: 12px; border: 1px solid #0d6efd;">Confirm</a>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                <tbody>
                <tr>
                    <td style="line-height: 16px; font-size: 15px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
                        &#160;
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <p class="" style="line-height: 24px; font-size: 15px; width: 100%; margin: 0;" align="left">
                If you can't click the link from your email program, please copy this URL and paste it into your web browser:
            </p>
            <br>
{{--            <p class="" style="line-height: 24px; font-size: 15px; width: 100%; margin: 0;" align="left">--}}
                <a style="line-height: 24px; font-size: 15px; width: 100%; margin: 0; color: revert; text-decoration: revert" href="{{ $details['link'] }}">{{ $details['link'] }}</a>
{{--            </p>--}}
            <br>
            <br>
            <p>
                Thanks,<br>
                {{ \Illuminate\Support\Facades\Config::get('app.name') }} Team
            </p>
        </td>
    </tr>
@endsection

