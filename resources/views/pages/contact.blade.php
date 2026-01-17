@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Contact Us</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">
                                <a href="mailto:support@azir-absensi.com" class="text-indigo-600 hover:text-indigo-700">
                                    support@azir-absensi.com
                                </a>
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Phone</h3>
                            <p class="text-gray-600">
                                <a href="tel:+62123456789" class="text-indigo-600 hover:text-indigo-700">
                                    +62 (123) 456-789
                                </a>
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Address</h3>
                            <p class="text-gray-600">
                                PT Duta Computer<br>
                                Jakarta, Indonesia
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Business Hours</h3>
                            <p class="text-gray-600">
                                Monday - Friday: 09:00 - 17:00<br>
                                Saturday - Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Support</h2>
                    <div class="space-y-4">
                        <div class="border-l-4 border-indigo-600 pl-4">
                            <h3 class="font-semibold text-gray-900">Technical Support</h3>
                            <p class="text-gray-600 text-sm">For technical issues and system support</p>
                        </div>
                        <div class="border-l-4 border-indigo-600 pl-4">
                            <h3 class="font-semibold text-gray-900">Sales Inquiry</h3>
                            <p class="text-gray-600 text-sm">For pricing and enterprise solutions</p>
                        </div>
                        <div class="border-l-4 border-indigo-600 pl-4">
                            <h3 class="font-semibold text-gray-900">Feedback</h3>
                            <p class="text-gray-600 text-sm">We'd love to hear your suggestions</p>
                        </div>
                        <div class="border-l-4 border-indigo-600 pl-4">
                            <h3 class="font-semibold text-gray-900">Documentation</h3>
                            <p class="text-gray-600 text-sm">Check our comprehensive guides and FAQs</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    <details class="border-b pb-4">
                        <summary class="font-semibold text-gray-900 cursor-pointer hover:text-indigo-600">
                            How do I reset my password?
                        </summary>
                        <p class="text-gray-600 mt-2">
                            Click on "Forgot Password" on the login page and follow the instructions sent to your email.
                        </p>
                    </details>
                    <details class="border-b pb-4">
                        <summary class="font-semibold text-gray-900 cursor-pointer hover:text-indigo-600">
                            Is my data secure?
                        </summary>
                        <p class="text-gray-600 mt-2">
                            Yes, we use industry-standard encryption and security protocols to protect your data.
                        </p>
                    </details>
                    <details class="border-b pb-4">
                        <summary class="font-semibold text-gray-900 cursor-pointer hover:text-indigo-600">
                            Can I export my attendance records?
                        </summary>
                        <p class="text-gray-600 mt-2">
                            Yes, you can export attendance records in PDF or Excel format from the Reports section.
                        </p>
                    </details>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
