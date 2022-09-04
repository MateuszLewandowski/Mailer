<?php

namespace App\Services;

use App\Interfaces\CaptchaInterface;
use App\Models\Captcha;
use App\Models\PendingRequest;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CaptchaService implements CaptchaInterface
{
    private array $operations = [
        1 => 'ADDITION',
        2 => 'SUBTRACTION',
        3 => 'DIVISION',
        4 => 'MULTIPLICATION',
    ];
    private int $operation_id;

    public function __construct(
    ) {
    }

    public function generate(int $request_id): array
    {
        try {
            [$x, $y, $result] = self::getMathIngredients();
            Captcha::create([
                'request_id' => $request_id,
                'operation_id' => $this->operation_id,
                'x' => $x,
                'y' => $y,
                'result' => $result,
            ]);
            return [
                'operation' => $this->operations[$this->operation_id],
                'x' => $x,
                'y' => $y,
                'result' => $result,
            ];
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function refresh(string $uuid): array
    {
        try {
            $captcha = DB::table('requests')
                ->selectRaw('requests.id as request_id, captcha.*')
                ->join('captcha', 'requests.id', '=', 'captcha.request_id')
                ->where('requests.uuid', '=', $uuid);
            [$x, $y, $result] = self::getMathIngredients();
            $result = DB::table('captcha')
                ->where('id', '=', $captcha->id)
                ->update([
                    'request_id' => $captcha->request_id,
                    'operation_id' => $this->operation_id,
                    'x' => $x,
                    'y' => $y,
                    'result' => $result,
                ]);
            return [
                'operation' => $this->operations[$this->operation_id],
                'x' => $x,
                'y' => $y,
                'result' => $result,
            ];
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public static function verify(int $obtained, int $expected): bool {
        return $obtained === $expected;
    }

    private static function getAnswerByUUID(string $uuid) {
        try {
            return DB::table('request')
                ->select('captcha.result')
                ->rightJoin('captcha', 'request.id', '=', 'captcha.request_id')
                ->where('request.uuid', '=', $uuid)
                ->firstOrFail();
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function getMathIngredients(): array
    {
        $this->operation_id = array_rand($this->operations);
        $operation = $this->operations[$this->operation_id];
        switch ($operation)
        {
            case 'SUBTRACTION':
                $x = self::getLargeEasyNumber();
                $y = self::getSmallNumber();
                return [$x, $y, $x - $y];
            case 'MULTIPLICATION':
                $x = self::getVerySmallNumber();
                $y = self::getVerySmallNumber();
                return [$x, $y, $x * $y];
            case 'DIVISION':
                [$x, $y] = self::getDivisionNumberPairs();
                return [$x, $y, $x / $y];
            case 'ADDITION':
            default:
                $x = self::getLargeEasyNumber();
                $y = self::getSmallNumber();
                return [$x, $y, $x + $y];
                break;
        }
    }

    private static function getLargeEasyNumber(): int {
        $x = mt_rand(3, 7);
        $y = mt_rand(1, 4);
        return (int) $x . $y;
    }

    private static function getSmallNumber(): int {
        return mt_rand(1, 6);
    }

    private static function getVerySmallNumber(): int {
        return mt_rand(1, 4);
    }

    private static function getDivisionNumberPairs(): array {
        $x = self::getSmallEvenNumber();
        $y = self::getVerySmallEvenNumber();
        if ($x % $y !== 0) {
            self::getDivisionNumberPairs();
        }
        return [$x, $y];
    }

    private static function getSmallEvenNumber(): int {
        return array_rand(array_flip([6, 8, 10, 12, 14, 16]));
    }

    private static function getVerySmallEvenNumber(): int {
        return array_rand(array_flip([2, 4]));
    }
}
