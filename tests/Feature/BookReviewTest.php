<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\UlasanBuku;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_can_create_and_update_one_review_per_book(): void
    {
        $anggota = User::factory()->anggota()->create();
        $buku = $this->buatBuku();

        $this->actingAs($anggota)
            ->post(route('anggota.buku.ulasan.simpan', $buku), [
                'rating' => 4,
                'comment' => 'Buku ini membantu saya memahami materi.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('book_reviews', [
            'book_id' => $buku->id,
            'user_id' => $anggota->id,
            'rating' => 4,
        ]);

        $this->actingAs($anggota)
            ->post(route('anggota.buku.ulasan.simpan', $buku), [
                'rating' => 5,
                'comment' => 'Setelah dibaca ulang, bukunya semakin bagus.',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('book_reviews', 1);
        $this->assertDatabaseHas('book_reviews', [
            'book_id' => $buku->id,
            'user_id' => $anggota->id,
            'rating' => 5,
            'comment' => 'Setelah dibaca ulang, bukunya semakin bagus.',
        ]);
    }

    public function test_review_validation_rejects_invalid_rating_and_empty_comment(): void
    {
        $anggota = User::factory()->anggota()->create();
        $buku = $this->buatBuku();

        $this->actingAs($anggota)
            ->from(route('anggota.buku.show', $buku))
            ->post(route('anggota.buku.ulasan.simpan', $buku), [
                'rating' => 6,
                'comment' => '',
            ])
            ->assertRedirect(route('anggota.buku.show', $buku))
            ->assertSessionHasErrors(['rating', 'comment']);
    }

    public function test_guest_and_pustakawan_cannot_submit_reviews(): void
    {
        $buku = $this->buatBuku();
        $pustakawan = User::factory()->pustakawan()->create();

        $this->post(route('anggota.buku.ulasan.simpan', $buku), [
            'rating' => 4,
            'comment' => 'Komentar dari guest.',
        ])->assertRedirect(route('anggota.login'));

        $this->actingAs($pustakawan)
            ->post(route('anggota.buku.ulasan.simpan', $buku), [
                'rating' => 4,
                'comment' => 'Komentar dari pustakawan.',
            ])
            ->assertForbidden();
    }

    public function test_reviews_are_visible_on_member_public_and_librarian_detail_pages(): void
    {
        $anggota = User::factory()->anggota()->create(['name' => 'Siti Aminah']);
        $pustakawan = User::factory()->pustakawan()->create();
        $buku = $this->buatBuku(['title' => 'Belajar Laravel']);

        UlasanBuku::create([
            'book_id' => $buku->id,
            'user_id' => $anggota->id,
            'rating' => 5,
            'comment' => 'Komentar ini tampil di semua detail.',
        ]);

        $this->actingAs($anggota)
            ->get(route('anggota.buku.show', $buku))
            ->assertOk()
            ->assertSee('Komentar ini tampil di semua detail.')
            ->assertSee('Siti Aminah');

        $this->get(route('buku.show', $buku))
            ->assertOk()
            ->assertSee('Komentar ini tampil di semua detail.')
            ->assertSee('Belajar Laravel');

        $this->actingAs($pustakawan)
            ->get(route('pustakawan.buku.show', $buku))
            ->assertOk()
            ->assertSee('Komentar ini tampil di semua detail.')
            ->assertSee('Detail Buku');
    }

    public function test_database_prevents_duplicate_reviews_for_same_member_and_book(): void
    {
        $anggota = User::factory()->anggota()->create();
        $buku = $this->buatBuku();

        UlasanBuku::create([
            'book_id' => $buku->id,
            'user_id' => $anggota->id,
            'rating' => 4,
            'comment' => 'Ulasan pertama.',
        ]);

        $this->expectException(QueryException::class);

        UlasanBuku::create([
            'book_id' => $buku->id,
            'user_id' => $anggota->id,
            'rating' => 5,
            'comment' => 'Ulasan duplikat.',
        ]);
    }

    private function buatBuku(array $attributes = []): Buku
    {
        $kategori = Kategori::create(['name' => 'Umum']);

        return Buku::factory()->create(array_merge([
            'category_id' => $kategori->id,
        ], $attributes));
    }
}
