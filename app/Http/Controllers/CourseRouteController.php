<?php

namespace App\Http\Controllers;

/**
 * Public route map for a race category: interactive 2D/3D map, elevation
 * profile and aid stations. Course settings come from config/courses.php and
 * the track from public/courses/{slug}.json (built by `course:build`).
 */
class CourseRouteController extends Controller
{
    public function __invoke(string $slug)
    {
        $course = config("courses.{$slug}");
        $file = public_path("courses/{$slug}.json");

        abort_unless($course && is_file($file), 404);

        // Each distance has its own brand color (100K orange, 60K red, ...).
        $accent = $course['accent'] ?? '#f97316';

        return view('race-category.route', [
            'slug'      => $slug,
            'course'    => $course,
            'trackUrl'  => asset("courses/{$slug}.json") . '?v=' . filemtime($file),
            'accent'    => $accent,
            'accentRgb' => implode(',', sscanf($accent, '#%02x%02x%02x')),
        ]);
    }
}
