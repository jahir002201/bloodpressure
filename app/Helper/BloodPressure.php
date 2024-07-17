<?php

namespace App\Helper;

class BloodPressure
{
    /**
     * Check the blood pressure and return the category and color code.
     *
     * @param int $systolic
     * @param int $diastolic
     * @return array
     */
    public static function checkPressure(int $systolic, int $diastolic): array
    {
        if ($systolic < 70 || $diastolic < 40) {
            return self::formatResponse('Low', 'blue');
        } elseif (($systolic >= 70 && $systolic < 90) || ($diastolic >= 40 && $diastolic < 60)) {
            return self::formatResponse('Low', 'blue');
        } elseif (($systolic >= 90 && $systolic < 120) && ($diastolic >= 60 && $diastolic < 80)) {
            return self::formatResponse('Normal', 'green');
        } elseif (($systolic >= 120 && $systolic < 140) || ($diastolic >= 80 && $diastolic < 90)) {
            return self::formatResponse('Pre-High', 'yellow');
        } elseif (($systolic >= 140 && $systolic <= 190) || ($diastolic >= 90 && $diastolic <= 100)) {
            return self::formatResponse('High', 'red');
        } else {
            return self::formatResponse('Invalid input', 'grey');
        }
    }

    /**
     * Format the response with category and color.
     *
     * @param string $category
     * @param string $color
     * @return array
     */
    private static function formatResponse(string $category, string $color): array
    {
        return [
            'category' => $category,
            'color' => $color
        ];
    }
}