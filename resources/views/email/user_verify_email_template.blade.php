@extends('email.layout.main')
@section('mail')
    <tr>
        <td class="wrapper" style=" box-sizing: border-box; padding: 20px;  ">
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
                <tr>
                    <td>
                        {{-- <h1 class="" "font-family: sans-serif; font-size: 20px; vertical-align: top; border-radius: 5px; text-align: center; background-color: #57b08d;" valign="top" align="center" bgcolor="#ec0867"> <div style="border: solid 1px #57b08d; border-radius: 5px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 20px; font-weight: bold; margin: 0; padding: 18px 25px; text-decoration: none; text-transform: capitalize; background-color: #57b08d; border-color: #57b08d; color: #ffffff;width:100%;">
                             [{{ $mailData['organization_name'] }}
                         </h1>--}}

                        <p> Dear, </p>
                        <br>

                        <p>  Please verify your email through this link</p>
                        <br>

                        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                        <br>
                        <a href="{{ $details['link'] }}">{{ $details['link'] }}</a>
                        <br>
                        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                        <br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection

