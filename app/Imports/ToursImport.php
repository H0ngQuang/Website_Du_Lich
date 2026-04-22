<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ToursImport implements ToCollection, WithHeadingRow
{
    protected $successCount = 0;
    protected $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 vì heading ở dòng 1, index bắt đầu từ 0

            try {
                // Validate required fields
                $requiredFields = [
                    'ten_tour' => 'Tên Tour',
                    'diem_den' => 'Điểm đến',
                    'khu_vuc' => 'Khu vực',
                    'so_luong' => 'Số lượng',
                    'gia_nguoi_lon' => 'Giá người lớn',
                    'gia_tre_em' => 'Giá trẻ em',
                    'ngay_bat_dau' => 'Ngày bắt đầu',
                    'ngay_ket_thuc' => 'Ngày kết thúc',
                    'mo_ta' => 'Mô tả',
                ];

                $missingFields = [];
                foreach ($requiredFields as $key => $label) {
                    if (empty($row[$key]) && $row[$key] !== 0) {
                        $missingFields[] = $label;
                    }
                }

                if (!empty($missingFields)) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Thiếu các trường: ' . implode(', ', $missingFields),
                    ];
                    continue;
                }

                // Validate khu vực
                $domain = strtolower(trim($row['khu_vuc']));
                if (!in_array($domain, ['b', 't', 'n'])) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Khu vực không hợp lệ: "' . $row['khu_vuc'] . '". Chỉ chấp nhận: b (Bắc), t (Trung), n (Nam)',
                    ];
                    continue;
                }

                // Parse dates
                $startDate = $this->parseDate($row['ngay_bat_dau']);
                $endDate = $this->parseDate($row['ngay_ket_thuc']);

                if (!$startDate) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Ngày bắt đầu không hợp lệ: "' . $row['ngay_bat_dau'] . '". Định dạng: dd/mm/yyyy',
                    ];
                    continue;
                }

                if (!$endDate) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Ngày kết thúc không hợp lệ: "' . $row['ngay_ket_thuc'] . '". Định dạng: dd/mm/yyyy',
                    ];
                    continue;
                }

                if ($endDate->lte($startDate)) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Ngày kết thúc phải sau ngày bắt đầu',
                    ];
                    continue;
                }

                // Validate numeric fields
                $quantity = intval($row['so_luong']);
                $priceAdult = floatval($row['gia_nguoi_lon']);
                $priceChild = floatval($row['gia_tre_em']);

                if ($quantity <= 0) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Số lượng phải lớn hơn 0',
                    ];
                    continue;
                }

                if ($priceAdult <= 0) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'message' => 'Giá người lớn phải lớn hơn 0',
                    ];
                    continue;
                }

                // Tính time
                $days = $startDate->diffInDays($endDate);
                $nights = $days - 1;
                $time = "{$days} ngày {$nights} đêm";

                // Insert vào database
                $tourId = DB::table('tbl_tours')->insertGetId([
                    'title' => trim($row['ten_tour']),
                    'time' => $time,
                    'description' => trim($row['mo_ta']),
                    'quantity' => $quantity,
                    'priceAdult' => $priceAdult,
                    'priceChild' => $priceChild,
                    'destination' => trim($row['diem_den']),
                    'domain' => $domain,
                    'availability' => 1,
                    'startDate' => $startDate->format('Y-m-d'),
                    'endDate' => $endDate->format('Y-m-d'),
                ]);

                // Insert images if provided
                if (!empty($row['hinh_anh'])) {
                    $images = array_map('trim', explode(',', $row['hinh_anh']));
                    foreach ($images as $img) {
                        if (!empty($img)) {
                            DB::table('tbl_images')->insert([
                                'tourId' => $tourId,
                                'imageUrl' => $img
                            ]);
                        }
                    }
                }

                $this->successCount++;

            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'message' => 'Lỗi: ' . $e->getMessage(),
                ];
            }
        }
    }

    /**
     * Parse date from various formats (dd/mm/yyyy, Excel serial number, etc.)
     */
    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Nếu là số (Excel serial date)
        if (is_numeric($value)) {
            try {
                return Carbon::createFromTimestamp(
                    ($value - 25569) * 86400
                );
            } catch (\Exception $e) {
                return null;
            }
        }

        // Nếu là string, thử parse dd/mm/yyyy
        $value = trim($value);
        try {
            return Carbon::createFromFormat('d/m/Y', $value);
        } catch (\Exception $e) {
            // Thử thêm format yyyy-mm-dd
            try {
                return Carbon::createFromFormat('Y-m-d', $value);
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
