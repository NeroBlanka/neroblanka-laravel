@props(['status'])

@php
$config = match($status) {
    'pending'     => ['bg-amber-50 text-amber-700',   'En attente'],
    'assigned'    => ['bg-blue-50 text-blue-700',     'Assigné'],
    'in_progress' => ['bg-violet-50 text-violet-700', 'En cours'],
    'submitted'   => ['bg-indigo-50 text-indigo-700', 'Livré'],
    'revision'    => ['bg-orange-50 text-orange-700', 'Révision'],
    'approved'    => ['bg-green-50 text-green-700',   'Approuvé'],
    'completed'   => ['bg-black/5 text-[#0a0a0a]',   'Terminé'],
    'cancelled'   => ['bg-[#e8e7e2] text-[#888780]', 'Annulé'],
    default       => ['bg-[#e8e7e2] text-[#888780]', ucfirst($status)],
};
@endphp

<span class="inline-flex items-center font-mono text-xs uppercase tracking-widest px-2.5 py-1 rounded {{ $config[0] }}">
    {{ $config[1] }}
</span>
