<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LibraryEvent;
use App\Models\EventRequest;
use App\Models\EventResponse;
use App\Models\User;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $librarian = User::where('role', 'librarian')->first();
        $members = User::where('role', 'member')->get();

        if (!$librarian || $members->count() < 1) {
            $this->command->warn('Need at least 1 librarian and 1 member to seed events');
            return;
        }

        $events = [
            [
                'title' => 'Workshop Kỹ Năng Đọc Hiệu Quả',
                'description' => 'Hội thảo chia sẻ các phương pháp đọc sách hiệu quả, ghi chú và tóm tắt nội dung.',
                'event_date' => Carbon::now()->addDays(7),
                'event_type' => 'Workshop',
                'location' => 'Phòng Hội Thảo - Tầng 2',
                'max_participants' => 30,
                'created_by' => $librarian->id,
                'status' => 'published',
            ],
            [
                'title' => 'Gặp Gỡ Tác Giả Nguyễn Nhật Ánh',
                'description' => 'Buổi gặp gỡ và giao lưu với nhà văn Nguyễn Nhật Ánh, ký tặng sách.',
                'event_date' => Carbon::now()->addDays(14),
                'event_type' => 'Gặp Gỡ Tác Giả',
                'location' => 'Sảnh Chính - Tầng 1',
                'max_participants' => 100,
                'created_by' => $librarian->id,
                'status' => 'published',
            ],
            [
                'title' => 'Câu Lạc Bộ Đọc Sách: Tháng 10',
                'description' => 'Thảo luận về cuốn sách "Nhà Giả Kim" của Paulo Coelho.',
                'event_date' => Carbon::now()->addDays(21),
                'event_type' => 'Câu Lạc Bộ Đọc Sách',
                'location' => 'Phòng Đọc - Tầng 3',
                'max_participants' => 20,
                'created_by' => $librarian->id,
                'status' => 'published',
            ],
            [
                'title' => 'Triển Lãm Sách Cổ Việt Nam',
                'description' => 'Triển lãm các đầu sách quý hiếm và sách cổ của Việt Nam qua các thời kỳ.',
                'event_date' => Carbon::now()->addDays(30),
                'event_type' => 'Triển Lãm',
                'location' => 'Khu Triển Lãm - Tầng 1',
                'max_participants' => null,
                'created_by' => $librarian->id,
                'status' => 'published',
            ],
            [
                'title' => 'Hội Thảo Văn Học Việt Nam Đương Đại',
                'description' => 'Buổi hội thảo về xu hướng và phát triển của văn học Việt Nam hiện đại.',
                'event_date' => Carbon::now()->subDays(7),
                'event_type' => 'Hội Thảo',
                'location' => 'Phòng Hội Thảo - Tầng 2',
                'max_participants' => 50,
                'created_by' => $librarian->id,
                'status' => 'completed',
            ],
        ];

        foreach ($events as $eventData) {
            $event = LibraryEvent::create($eventData);

            if ($members->count() > 0 && ($event->status === 'published' || $event->status === 'completed')) {
                $responseCount = min(3, $members->count());
                foreach ($members->random($responseCount) as $member) {
                    EventResponse::create([
                        'event_id' => $event->id,
                        'member_id' => $member->id,
                        'response_type' => collect(['interested', 'attending', 'not_attending'])->random(),
                        'comment' => collect([
                            'Rất mong chờ sự kiện này!',
                            'Chủ đề rất hấp dẫn.',
                            'Tôi sẽ tham gia cùng bạn bè.',
                            null,
                        ])->random(),
                    ]);
                }
            }
        }

        if ($members->count() > 0) {
            EventRequest::create([
                'member_id' => $members->first()->id,
                'title' => 'Workshop Viết Review Sách',
                'description' => 'Đề xuất tổ chức workshop hướng dẫn viết review sách chuyên nghiệp.',
                'requested_event_date' => Carbon::now()->addDays(45),
                'status' => 'pending',
            ]);

            EventRequest::create([
                'member_id' => $members->skip(1)->first()->id ?? $members->first()->id,
                'title' => 'Buổi Chiếu Phim Chuyển Thể Từ Sách',
                'description' => 'Đề xuất tổ chức buổi chiếu phim chuyển thể từ tiểu thuyết nổi tiếng.',
                'requested_event_date' => Carbon::now()->addDays(60),
                'status' => 'approved',
                'reviewed_by' => $librarian->id,
                'review_note' => 'Ý tưởng hay, chúng tôi sẽ tổ chức trong tháng tới.',
                'reviewed_at' => Carbon::now()->subDays(2),
            ]);
        }
    }
}
