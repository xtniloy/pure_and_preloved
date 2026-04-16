@extends('public.layouts.main')
@section('title')
    Terms & Conditions
@endsection
@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Terms & Conditions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .terms-page p { margin-bottom: 1rem; }
        .terms-page ul, .terms-page ol { list-style-position: outside; padding-left: 1.25rem; margin-bottom: 1rem; }
        .terms-page ul { list-style-type: disc; }
        .terms-page ul ul { list-style-type: circle; margin-top: .5rem; }
        .terms-page ol { list-style-type: decimal; }
        .terms-page li { display: list-item; margin-bottom: .35rem; }
        .terms-page li > ul, .terms-page li > ol { margin-top: .5rem; margin-bottom: .5rem; }
    </style>

    <div class="container mb-50px mt-50px terms-page">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="mb-2 fw-bold">Terms & Conditions</h1>
                        <p class="text-muted mb-4">Last Updated: 01 January 2026</p>

                        <p>
                            Welcome to <strong>Pure and Preloved Limited</strong>. By purchasing from us, you agree to the following Terms &
                            Conditions. These policies ensure clarity, fairness, and protection for both our customers and our business.
                        </p>

                        <hr class="my-4">

                        <h4 class="mt-4">1. Product Condition & Descriptions</h4>
                        <ul>
                            <li>All items are <strong>pre-owned or vintage</strong>, unless stated otherwise.</li>
                            <li>Products are sold strictly on a <strong>“sold as seen”</strong> basis.</li>
                            <li>We provide detailed photos, descriptions, and measurements.</li>
                            <li>
                                Minor variations may occur due to:
                                <ul>
                                    <li>Manual measurement limitations</li>
                                    <li>Tool variations</li>
                                    <li>Settings that obscure exact dimensions</li>
                                </ul>
                            </li>
                            <li>These variations do not qualify as misrepresentation.</li>
                            <li>Colours may vary slightly due to lighting or screen differences.</li>
                        </ul>

                        <h4 class="mt-4">2. Postage & Delivery</h4>
                        <ul>
                            <li>Orders are shipped via <strong>Royal Mail or Evri</strong>.</li>
                            <li>Postage costs are <strong>non-refundable</strong> unless we made an error.</li>
                            <li>Delivery times are the responsibility of the courier once dispatched.</li>
                            <li>We do <strong>not offer international shipping</strong>.</li>
                        </ul>

                        <h4 class="mt-4">3. Refunds, Returns & Exchanges</h4>

                        <h5 class="mt-3">3.1 Refund Policy</h5>
                        <ul>
                            <li>No refunds on preloved items.</li>
                            <li>
                                This includes:
                                <ul>
                                    <li>Change of mind</li>
                                    <li>Buyer error</li>
                                    <li>Minor variations in size, weight, or colour</li>
                                    <li>Normal wear of preloved jewellery</li>
                                </ul>
                            </li>
                        </ul>

                        <h5 class="mt-3">3.2 Exchange Policy</h5>
                        <ul>
                            <li>Exchanges allowed within <strong>7 days</strong> of receiving the item.</li>
                            <li>Items are unique; identical replacements are unlikely.</li>
                            <li>
                                You must select:
                                <ul>
                                    <li>Item of equal value, or</li>
                                    <li>Higher value (pay the difference)</li>
                                </ul>
                            </li>
                        </ul>

                        <h5 class="mt-3">3.3 Return Conditions</h5>
                        <ul>
                            <li>Item must be returned in original condition.</li>
                            <li>Return shipping is paid by the buyer.</li>
                            <li>We are not responsible for lost or damaged returns.</li>
                            <li>Use tracked & insured shipping.</li>
                        </ul>

                        <h4 class="mt-4">4. Hallmarking & Certification</h4>
                        <ul>
                            <li>All items are <strong>British hallmarked</strong>.</li>
                            <li>No certificates or valuations are provided.</li>
                            <li>Independent valuations are at buyer’s discretion.</li>
                        </ul>

                        <h4 class="mt-4">5. Liability</h4>
                        <ul>
                            <li>
                                We are not liable for:
                                <ul>
                                    <li>Allergic reactions</li>
                                    <li>Improper handling or cleaning damage</li>
                                    <li>Normal wear and tear</li>
                                </ul>
                            </li>
                            <li>Customers should insure jewellery after purchase.</li>
                            <li>No liability for third-party repairs or alterations.</li>
                        </ul>

                        <h5 class="mt-3">5.1 Statutory Rights</h5>
                        <p>
                            These Terms do not affect your rights under the
                            <strong>Consumer Rights Act 2015</strong>.
                        </p>

                        <h4 class="mt-4">6. Contact Information</h4>
                        <p class="mb-0">
                            <strong>Pure and Preloved Limited</strong><br>
                            Company Number: 16918159<br>
                            Email: <a href="mailto:support@pureandpreloved.co.uk">support@pureandpreloved.co.uk</a><br>
                            Phone: +44 7396 823194
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
