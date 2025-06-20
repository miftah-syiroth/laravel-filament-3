<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $institutions = [
            'Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada',
            'Universitas Brawijaya', 'Universitas Airlangga', 'Institut Pertanian Bogor',
            'Universitas Padjadjaran', 'Universitas Diponegoro', 'Universitas Hasanuddin',
            'SMA Negeri 1 Jakarta', 'SMA Negeri 1 Bandung', 'SMA Negeri 1 Surabaya',
            'SMP Negeri 1 Jakarta', 'SMP Negeri 1 Bandung', 'SMP Negeri 1 Surabaya',
            'SD Negeri 1 Jakarta', 'SD Negeri 1 Bandung', 'SD Negeri 1 Surabaya'
        ];

        $majors = [
            'Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer',
            'Matematika', 'Fisika', 'Kimia', 'Biologi',
            'Ekonomi', 'Manajemen', 'Akuntansi',
            'Hukum', 'Psikologi', 'Sosiologi',
            'Bahasa Indonesia', 'Bahasa Inggris', 'Sejarah',
            'IPA', 'IPS', 'Bahasa'
        ];

        $startDate = fake()->dateTimeBetween('-15 years', '-5 years');
        $endDate = fake()->dateTimeBetween($startDate, 'now');

        return [
            'institution' => fake()->randomElement($institutions),
            'url' => fake()->url(),
            'major' => fake()->randomElement($majors),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
