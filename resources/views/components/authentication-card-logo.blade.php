@php $settings = \App\Models\CompanySetting::get(); @endphp
<a href="/">
    @if($settings->logo_path)
        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="{{ $settings->business_name }}" class="h-16 w-auto object-contain" />
    @else
        <div class="h-16 w-16 rounded-full bg-indigo-600 flex items-center justify-center mx-auto">
            <span class="text-white text-2xl font-bold">{{ strtoupper(substr($settings->business_name, 0, 2)) }}</span>
        </div>
    @endif
</a>
