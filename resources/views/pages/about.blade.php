@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">About PT DUTA COMPUTER</h1>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
                <p class="text-gray-600 mb-4">
                    PT DUTA COMPUTER adalah sistem manajemen absensi modern yang dirancang untuk menyederhanakan pelacakan karyawan dan manajemen cuti untuk organisasi dari semua ukuran. Kami percaya dalam membuat manajemen absensi sederhana, transparan, dan efisien.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Why Choose Us?</h2>
                <ul class="space-y-4 text-gray-600">
                    <li class="flex items-start">
                        <span class="text-indigo-600 font-bold mr-3">•</span>
                        <span><strong>Easy to Use:</strong> Intuitive interface that requires minimal training</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-indigo-600 font-bold mr-3">•</span>
                        <span><strong>Secure:</strong> Enterprise-grade security with data encryption</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-indigo-600 font-bold mr-3">•</span>
                        <span><strong>Scalable:</strong> Works for small teams to large enterprises</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-indigo-600 font-bold mr-3">•</span>
                        <span><strong>Reliable:</strong> 99.9% uptime guarantee with regular backups</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-indigo-600 font-bold mr-3">•</span>
                        <span><strong>Support:</strong> Dedicated customer support team available 24/7</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Technology Stack</h2>
                <p class="text-gray-600 mb-4">
                    Built with modern technologies to ensure reliability, performance, and security:
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="font-semibold text-gray-900">Backend</p>
                        <p class="text-gray-600">Laravel 11</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="font-semibold text-gray-900">Frontend</p>
                        <p class="text-gray-600">Vue.js 3 & Tailwind CSS</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="font-semibold text-gray-900">Database</p>
                        <p class="text-gray-600">MySQL/PostgreSQL</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="font-semibold text-gray-900">Mobile</p>
                        <p class="text-gray-600">React Native</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('home') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
