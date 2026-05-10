@php $settings = \App\Models\CompanySetting::get(); @endphp
@if($settings->logo_path)
    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="{{ $settings->business_name }}" {{ $attributes->merge(['class' => 'object-contain']) }} />
@else
    <div {{ $attributes->merge(['class' => 'rounded-full bg-indigo-600 flex items-center justify-center']) }}>
        <span class="text-white font-bold">{{ strtoupper(substr($settings->business_name, 0, 2)) }}</span>
    </div>
@endif
