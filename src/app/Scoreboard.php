<?php
namespace TmlpStats;

/**
 * Class Scoreboard
 *
 * Contains helper methods for calculating and working with the games scoreboard
 *
 * @package TmlpStats
 */
class Scoreboard
{
    const MAX_POINTS = 28;
    const MIN_POINTS = 0;

    protected static $ratingsByPoints = [
        '28' => 'Powerful',
        '22' => 'High Performing',
        '16' => 'Effective',
        '9'  => 'Marginally Effective',
        '0'  => 'Ineffective',
    ];

    protected static $pointsByPercent = [
        '100' => 4,
        '90'  => 3,
        '80'  => 2,
        '75'  => 1,
    ];

    protected static $games = [
        'cap'  => ['x' => 2],
        'cpc'  => ['x' => 1],
        't1x'  => ['x' => 1],
        't2x'  => ['x' => 1],
        'gitw' => ['x' => 1],
        'lf'   => ['x' => 1],
    ];

    /**
     * Calculate the number of points for the give performance
     *
     * @param $promises object with game properties
     * @param $actuals  object with game properties
     *
     * @return int
     * @throws \Exception
     */
    public static function calculatePoints($promises, $actuals)
    {
        if (!$promises || !$actuals) {
            throw new \Exception('Invalid argument passed to ' . __FUNCTION__);
        }

        $points = 0;
        $games  = array_keys(static::$games);
        foreach ($games as $game) {
            $promise = $promises->$game;
            $actual  = $actuals->$game;

            $percent = static::calculatePercent($promise, $actual);
            $points += static::getPoints($percent, $game);
        }

        return $points;
    }

    /**
     * Get the integer percentage of actual performance against promise
     *
     * @param integer $actual
     * @param integer $promise
     *
     * @return integer
     */
    public static function calculatePercent($promise, $actual)
    {
        if ($promise <= 0) {
            return 0;
        }

        $percent = round(($actual / $promise) * 100);

        return max(min($percent, 100), 0);
    }

    /**
     * Get the points based on game percentage
     *
     * @param integer $percent
     * @param string $game
     *
     * @return integer
     */
    public static function getPoints($percent, $game)
    {
        $game = strtolower($game);
        $points = 0;

        if (!isset(static::$games[$game])) {
            throw new \Exception("Unknown game {$game}");
        }

        foreach (static::$pointsByPercent as $gamePercent => $gamePoints) {
            if ($percent >= $gamePercent) {
                $points = $gamePoints;
                break;
            }
        }

        $multiplier = isset(static::$games[$game]['x'])
            ? static::$games[$game]['x']
            : 1;

        return ($points * $multiplier);
    }

    /**
     * Get the rating based on number of points
     *
     * @param integer $points
     *
     * @return string
     */
    public static function getRating($points)
    {
        if ($points > static::MAX_POINTS || $points < static::MIN_POINTS) {
            throw new \Exception("Points {$points} is out of range.");
        }

        foreach (static::$ratingsByPoints as $ratingPoints => $rating) {
            if ($points >= $ratingPoints) {
                return $rating;
            }
        }

        return static::$ratingsByPoints[static::MIN_POINTS];
    }
}
