@props(['status'])

@php
$status = $status instanceof \BackedEnum ? $status->value : (string) $status;
[$textColor, $bgColor, $borderColor, $label] = match($status) {
    'pending'     => ['#fbbf24', 'rgba(251,191,36,0.08)',   'rgba(251,191,36,0.3)',   'En attente'],
    'assigned'    => ['#60a5fa', 'rgba(96,165,250,0.08)',   'rgba(96,165,250,0.3)',   'Assigné'],
    'in_progress' => ['#c4b5fd', 'rgba(167,139,250,0.10)', 'rgba(167,139,250,0.35)', 'En cours'],
    'submitted'   => ['#a5b4fc', 'rgba(129,140,248,0.08)', 'rgba(129,140,248,0.3)',  'Livré'],
    'revision'    => ['#fb923c', 'rgba(251,146,60,0.08)',   'rgba(251,146,60,0.3)',   'Révision'],
    'approved'    => ['#34d399', 'rgba(52,211,153,0.08)',   'rgba(52,211,153,0.3)',   'Approuvé'],
    'completed'   => ['#c8c5bc', 'rgba(200,197,188,0.06)', 'rgba(200,197,188,0.15)', 'Terminé'],
    'active'      => ['#6ee7b7', 'rgba(52,211,153,0.08)',   'rgba(52,211,153,0.3)',   'Actif'],
    'draft'       => ['#7a7875', 'rgba(122,120,117,0.06)', 'rgba(122,120,117,0.2)',  'Draft'],
    'cancelled'   => ['#5a5855', 'rgba(90,88,85,0.05)',    'rgba(90,88,85,0.15)',    'Annulé'],
    default       => ['#8a8784', 'rgba(138,135,132,0.06)', 'rgba(138,135,132,0.2)',  ucfirst($status)],
};
@endphp

<span class="inline-flex items-center font-mono text-[10px] uppercase tracking-[0.15em] px-2.5 py-1 rounded-lg"
      style="
        color: {{ $textColor }};
        background: {{ $bgColor }};
        border: 1px solid {{ $borderColor }};
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
      ">
    {{ $label }}
</span>
