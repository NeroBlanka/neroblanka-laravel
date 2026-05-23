<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, sans-serif; background: #f5f5f5; margin: 0; padding: 32px 16px; color: #0a0a0a; }
        .container { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 2px; }
        .header { background: #0a0a0a; padding: 24px 32px; }
        .header h1 { color: #ffffff; font-size: 15px; font-weight: 600; margin: 0; }
        .score { display: inline-block; padding: 2px 8px; border-radius: 2px; font-size: 13px; font-weight: 600; background: {{ $lead->isHot() ? '#16a34a' : ($lead->isWarm() ? '#ca8a04' : '#dc2626') }}; color: white; }
        .body { padding: 28px 32px; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; font-size: 13px; }
        .label { color: #888; }
        .value { color: #0a0a0a; font-weight: 500; }
        .brief { margin-top: 20px; padding: 16px; background: #f9f9f9; border-radius: 2px; font-size: 13px; line-height: 1.6; color: #555; }
        .cta { display: inline-block; margin-top: 24px; padding: 12px 24px; background: #0a0a0a; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 2px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouveau lead · <span class="score">{{ $lead->score }}/100</span></h1>
        </div>
        <div class="body">
            <div class="row"><span class="label">Nom</span><span class="value">{{ $lead->full_name }}</span></div>
            <div class="row"><span class="label">Email</span><span class="value">{{ $lead->email }}</span></div>
            @if($lead->phone)
                <div class="row"><span class="label">Téléphone</span><span class="value">{{ $lead->phone }}</span></div>
            @endif
            @if($lead->company)
                <div class="row"><span class="label">Entreprise</span><span class="value">{{ $lead->company }}</span></div>
            @endif
            <div class="row"><span class="label">Service</span><span class="value">{{ $lead->service_type->label() }}</span></div>
            <div class="row"><span class="label">Budget</span><span class="value">{{ $lead->budget_range }}</span></div>
            <div class="row"><span class="label">Délai</span><span class="value">{{ $lead->deadline_range }}</span></div>
            @if($lead->client_type)
                <div class="row"><span class="label">Type client</span><span class="value">{{ $lead->client_type }}</span></div>
            @endif

            @if($lead->brief)
                <div class="brief">
                    <strong>Brief:</strong><br>
                    {{ $lead->brief->answers['project_description'] ?? '' }}
                </div>
            @endif

            <a href="{{ url('/admin/leads/' . $lead->id) }}" class="cta">Voir dans le CRM →</a>
        </div>
    </div>
</body>
</html>
