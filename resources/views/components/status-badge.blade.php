@props(['status'])

@php
$status = $status instanceof \BackedEnum ? $status->value : (string) $status;
[$textColor, $bgColor, $borderColor, $label] = match($status) {
    'pending'             => ['#8a6500', 'rgba(251,191,36,0.10)', 'rgba(200,127,10,0.30)',  'En attente'],
    'assigned'            => ['#1d4ed8', 'rgba(37,99,235,0.06)',  'rgba(37,99,235,0.25)',   'Assigné'],
    'active'              => ['#15803d', 'rgba(31,157,85,0.08)',  'rgba(31,157,85,0.30)',   'Actif'],
    'in_progress'         => ['#5b21b6', 'rgba(124,58,237,0.06)', 'rgba(124,58,237,0.25)',  'En cours'],
    'submitted'           => ['#1d4ed8', 'rgba(37,99,235,0.06)',  'rgba(37,99,235,0.25)',   'Livré'],
    'revision'            => ['#9a3412', 'rgba(234,88,12,0.06)',  'rgba(234,88,12,0.25)',   'Révision'],
    'revision_requested'  => ['#9a3412', 'rgba(234,88,12,0.06)',  'rgba(234,88,12,0.25)',   'Révision demandée'],
    'waiting_client'      => ['#5b21b6', 'rgba(124,58,237,0.06)', 'rgba(124,58,237,0.25)',  'Attente client'],
    'approved'            => ['#15803d', 'rgba(31,157,85,0.08)',  'rgba(31,157,85,0.30)',   'Approuvé'],
    'completed'           => ['#374151', 'rgba(55,65,81,0.05)',   'rgba(55,65,81,0.20)',    'Terminé'],
    'draft'               => ['#6E6A64', 'rgba(110,106,100,0.05)', 'rgba(110,106,100,0.20)','Brouillon'],
    'cancelled'           => ['#7f1d1d', 'rgba(185,28,28,0.04)',  'rgba(185,28,28,0.20)',   'Annulé'],
    default               => ['#6E6A64', 'rgba(110,106,100,0.05)', 'rgba(110,106,100,0.20)', ucfirst(str_replace('_', ' ', $status))],
};
@endphp

<span class="inline-flex items-center text-[10px] uppercase font-medium tracking-[0.14em] px-2.5 py-1 rounded-full"
      style="color: {{ $textColor }}; background: {{ $bgColor }}; border: 1px solid {{ $borderColor }};">
    {{ $label }}
</span>
