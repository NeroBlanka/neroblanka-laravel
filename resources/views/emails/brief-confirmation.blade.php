<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DM Sans', -apple-system, sans-serif; background: #f5f5f5; margin: 0; padding: 32px 16px; color: #0a0a0a; }
        .container { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 2px; overflow: hidden; }
        .header { background: #0a0a0a; padding: 32px; }
        .header h1 { color: #ffffff; font-size: 20px; font-weight: 600; margin: 0; letter-spacing: -0.02em; }
        .body { padding: 32px; }
        .body p { font-size: 15px; line-height: 1.6; color: #555; margin: 0 0 16px; }
        .highlight { color: #0a0a0a; font-weight: 500; }
        .cta { display: inline-block; margin-top: 24px; padding: 12px 24px; background: #0a0a0a; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 2px; }
        .footer { padding: 24px 32px; border-top: 1px solid #f0f0f0; }
        .footer p { font-size: 12px; color: #888; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Neroblanka</h1>
        </div>
        <div class="body">
            <p>Bonjour <span class="highlight">{{ $lead->full_name }}</span>,</p>
            <p>
                Votre diagnostic créatif a bien été reçu. Nadir analysera votre projet dans les <span class="highlight">48 heures</span>
                et vous contactera directement.
            </p>
            <p>
                Service demandé : <span class="highlight">{{ $lead->service_type->label() }}</span>
            </p>
            <p style="margin-top: 24px; font-size: 13px; color: #888;">
                Pas de réponse automatique ici — vous aurez une vraie réponse, pensée pour votre projet.
            </p>
        </div>
        <div class="footer">
            <p>Neroblanka Studio · Du contraste naît la clarté.</p>
        </div>
    </div>
</body>
</html>
