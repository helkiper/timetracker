<?php
/**
 * Created by PhpStorm.
 * User: Hellkiper
 * Date: 25.03.2019
 * Time: 10:27
 */

namespace AppBundle\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('duration', [$this, 'durationFilter'])
        ];
    }

    /**
     * @param int $duration
     * @return string
     * @throws \Exception
     */
    public function durationFilter($duration)
    {
        $seconds = $duration % 60;
        $rest = ($duration - $seconds) / 60;
        $minutes = $rest % 60;
        $hours = ($rest - $minutes) / 60;

        $seconds = $seconds < 10 ? '0' . $seconds : $seconds;
        $minutes = $minutes < 10 ? '0' . $minutes : $minutes;
        $hours = $hours < 10 ? '0' . $hours : $hours;

        return $hours . ':' . $minutes . ':' . $seconds;
    }
}