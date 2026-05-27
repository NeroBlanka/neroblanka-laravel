<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;

class FreelanceMatchingService
{
    /**
     * Retourne les freelances classés par score de compatibilité avec le projet.
     * Chaque élément : ['freelance' => User, 'score' => int, 'breakdown' => array]
     */
    public function rankForProject(Project $project): Collection
    {
        $serviceType = is_string($project->service_type)
            ? $project->service_type
            : $project->service_type?->value;

        $freelances = User::where('role', 'freelance')
            ->with(['freelanceProfile', 'skills', 'assignments' => fn($q) => $q->where('status', 'active')])
            ->get();

        return $freelances
            ->map(fn($f) => $this->scoreFreelance($f, $serviceType))
            ->sortByDesc('score')
            ->values();
    }

    private function scoreFreelance(User $freelance, ?string $serviceType): array
    {
        $breakdown = [];

        // Service match (30 pts)
        $serviceScore = $this->scoreService($freelance, $serviceType);
        $breakdown['service'] = $serviceScore;

        // Disponibilité (20 pts)
        $availabilityScore = $freelance->is_available ? 20 : 0;
        $breakdown['availability'] = $availabilityScore;

        // Compétences (20 pts) — richesse du profil skills + spécialités
        $skillScore = $this->scoreSkills($freelance, $serviceType);
        $breakdown['skills'] = $skillScore;

        // Rating (15 pts) — rating/5 * 15
        $rating = (float) ($freelance->freelanceProfile?->rating ?? 0);
        $ratingScore = (int) round($rating / 5 * 15);
        $breakdown['rating'] = $ratingScore;

        // Charge de travail (10 pts) — moins d'assignments actifs = mieux
        $activeCount = $freelance->assignments->count();
        $workloadScore = match(true) {
            $activeCount === 0 => 10,
            $activeCount === 1 => 7,
            $activeCount === 2 => 4,
            $activeCount === 3 => 1,
            default => 0,
        };
        $breakdown['workload'] = $workloadScore;

        // Profil complet (5 pts)
        $profile = $freelance->freelanceProfile;
        $profileScore = 0;
        if ($profile?->bio) $profileScore += 2;
        if ($profile?->portfolio_url) $profileScore += 2;
        if ($profile?->completed_count > 0) $profileScore += 1;
        $breakdown['profile'] = $profileScore;

        $total = array_sum($breakdown);

        return [
            'freelance' => $freelance,
            'score' => min(100, $total),
            'breakdown' => $breakdown,
        ];
    }

    private function scoreService(User $freelance, ?string $serviceType): int
    {
        if (! $serviceType) {
            return 10;
        }

        $profileServiceTypes = $freelance->freelanceProfile?->service_types ?? [];
        $specialties = $freelance->specialties ?? [];

        $allServices = array_merge(
            array_map('strtolower', (array) $profileServiceTypes),
            array_map('strtolower', (array) $specialties),
        );

        if (in_array(strtolower($serviceType), $allServices, true)) {
            return 30;
        }

        // Correspondance partielle par catégorie (ex: branding ↔ mixed_project)
        if ($serviceType === 'mixed_project' && ! empty($allServices)) {
            return 15;
        }

        // Si le freelance a des services déclarés mais aucun match → 5 pts quand même
        if (! empty($allServices)) {
            return 5;
        }

        return 0;
    }

    private function scoreSkills(User $freelance, ?string $serviceType): int
    {
        $skillCount = $freelance->skills->count();

        // Score de base sur le nombre de compétences déclarées
        $base = match(true) {
            $skillCount >= 6 => 15,
            $skillCount >= 3 => 10,
            $skillCount >= 1 => 5,
            default => 0,
        };

        // +5 bonus si au moins une compétence correspond au service recherché
        $bonus = 0;
        if ($serviceType) {
            $hasRelevantSkill = $freelance->skills->contains(
                fn($s) => str_contains(strtolower($s->name), strtolower(str_replace('_', ' ', $serviceType)))
                    || ($s->category && str_contains(strtolower($serviceType), strtolower($s->category)))
            );
            if ($hasRelevantSkill) {
                $bonus = 5;
            }
        }

        return min(20, $base + $bonus);
    }
}
