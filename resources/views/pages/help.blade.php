@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Help & Documentation</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-3xl mb-4">📖</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Getting Started</h3>
                    <p class="text-gray-600 mb-4">Learn the basics of using PT DUTA COMPUTER system</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Account setup and login</li>
                        <li>• Profile configuration</li>
                        <li>• First attendance marking</li>
                        <li>• Mobile app installation</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-3xl mb-4">⏰</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Attendance</h3>
                    <p class="text-gray-600 mb-4">Master attendance tracking features</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Clock in/out procedures</li>
                        <li>• QR code scanning</li>
                        <li>• Location tracking</li>
                        <li>• Overtime management</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-3xl mb-4">📋</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Leave Management</h3>
                    <p class="text-gray-600 mb-4">Handle leave requests and approvals</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Submit leave requests</li>
                        <li>• Check leave balance</li>
                        <li>• Approve/reject requests</li>
                        <li>• Leave history</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-3xl mb-4">📊</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Reports</h3>
                    <p class="text-gray-600 mb-4">Generate and analyze reports</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Attendance reports</li>
                        <li>• Leave reports</li>
                        <li>• Export to PDF/Excel</li>
                        <li>• Custom date ranges</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-3xl mb-4">👤</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Profile</h3>
                    <p class="text-gray-600 mb-4">Manage your account settings</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• Edit personal information</li>
                        <li>• Change password</li>
                        <li>• Two-factor authentication</li>
                        <li>• Notification preferences</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="text-3xl mb-4">⚙️</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Administration</h3>
                    <p class="text-gray-600 mb-4">Admin panel features and management</p>
                    <ul class="space-y-2 text-gray-600">
                        <li>• User management</li>
                        <li>• Role and permissions</li>
                        <li>• Department setup</li>
                        <li>• System settings</li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">I can't log in to my account</h3>
                        <p class="text-gray-600">
                            Check that you're using the correct email and password. If you've forgotten your password, use the "Forgot Password" link on the login page. If you still can't access your account, contact our support team.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">QR code scanning isn't working</h3>
                        <p class="text-gray-600">
                            Ensure your device has camera permissions enabled. Try restarting the app or clearing your browser cache. Make sure the QR code is clearly visible and well-lit.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">My location isn't being tracked</h3>
                        <p class="text-gray-600">
                            Check that location services are enabled on your device and that you've granted permission to the app. Ensure you have a stable internet connection.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">I'm not receiving notifications</h3>
                        <p class="text-gray-600">
                            Check your notification settings in your profile. Ensure notifications are enabled for the app on your device. Check your email spam folder for email notifications.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 border-l-4 border-indigo-600 p-8 rounded mb-12">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Need More Help?</h2>
                <p class="text-gray-600 mb-4">
                    If you can't find the answer you're looking for, our support team is here to help.
                </p>
                <a href="{{ route('contact') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Contact Support
                </a>
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
