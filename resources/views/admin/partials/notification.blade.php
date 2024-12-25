<div id="notification">
    @if($errors->any())
        {!! implode('', $errors->all(
        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error! </strong> :message!
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>')) !!}
    @endif

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success. </strong> {!!  Session::get('success') !!}
            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error! </strong> {!!  Session::get('error') !!}
                <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif

    @if(Session::has('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Information: </strong> {!!  Session::get('info') !!}
                <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif

    @if(Session::has('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning! </strong> {!!  Session::get('warning') !!}
                <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif

    @if(Session::has('message'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Message:</strong> {!!  Session::get('message') !!}
                <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif
</div>
