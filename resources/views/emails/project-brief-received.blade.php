<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nouveau brief</title>
<style>
  body { margin: 0; padding: 0; background-color: #0a0a0a; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
  .wrapper { max-width: 600px; margin: 0 auto; padding: 48px 24px; }
  .logo { font-size: 18px; font-weight: 700; letter-spacing: 0.15em; color: #ffffff; text-transform: uppercase; margin-bottom: 48px; }
  .card { background-color: #111111; border: 1px solid #222222; padding: 40px; }
  .label { font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: #555555; margin-bottom: 4px; }
  .value { font-size: 15px; color: #cccccc; margin-bottom: 24px; }
  .title { font-size: 22px; font-weight: 700; color: #ffffff; margin-bottom: 32px; }
  .divider { border: none; border-top: 1px solid #1e1e1e; margin: 32px 0; }
  .footer { font-size: 12px; color: #333333; text-align: center; margin-top: 40px; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="logo">Neroblanka</div>
  <div class="card">
    <div class="title">Nouveau brief reçu</div>
    <div class="label">Projet</div>
    <div class="value">{{ $project->title }}</div>
    <div class="label">Client</div>
    <div class="value">{{ $project->client->full_name }} &lt;{{ $project->client->email }}&gt;</div>
    <div class="label">Service</div>
    <div class="value">{{ $project->service_type ?? '—' }}</div>
    <div class="label">Budget</div>
    <div class="value">{{ $project->budget_da ? number_format($project->budget_da) . ' DA' : '—' }}</div>
    <div class="label">Deadline</div>
    <div class="value">{{ $project->deadline ? $project->deadline->format('d/m/Y') : '—' }}</div>
    <hr class="divider">
    <div class="label">Description</div>
    <div class="value">{{ $project->description }}</div>
  </div>
  <div class="footer">Neroblanka Studio &mdash; Alger</div>
</div>
</body>
</html>
