<?php

namespace Database\Seeders;

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds the starter catalog ported from the uxlab podcast lab. Idempotent:
 * keyed on slug so it can be re-run without duplicating rows.
 */
class PodcastSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->orderBy('id')->first()
            ?? User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);

        foreach ($this->podcasts() as $slug => $podcast) {
            $episodes = $podcast['episodes'];
            unset($podcast['episodes']);

            $model = Podcast::query()->updateOrCreate(
                ['slug' => $slug],
                [...$podcast, 'user_id' => $owner->id],
            );

            foreach ($episodes as $number => $episode) {
                Episode::query()->updateOrCreate(
                    ['podcast_id' => $model->id, 'slug' => sprintf('%s-%03d', $slug, $number + 1)],
                    [
                        ...$episode,
                        'audio_url' => sprintf('https://www.soundhelix.com/examples/mp3/SoundHelix-Song-%d.mp3', ($number % 9) + 1),
                    ],
                );
            }
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function podcasts(): array
    {
        return [
            'full-stack-radio' => [
                'title' => 'Full Stack Radio',
                'author' => 'Adam Wathan',
                'description' => 'A podcast for developers interested in building great software products. Hosted by Adam Wathan, the creator of Tailwind CSS.',
                'website' => 'https://fullstackradio.com',
                'feed_url' => 'https://feeds.transistor.fm/full-stack-radio',
                'episodes' => [
                    [
                        'title' => 'Building Tailwind UI with Steve Schoger',
                        'description' => 'Adam talks with Steve Schoger about the design process behind Tailwind UI, creating a component library, and tips for designing better interfaces.',
                        'show_notes' => "In this episode, Adam is joined by Steve Schoger to talk about:\n- The design process for Tailwind UI\n- Building component libraries from scratch\n- Tips for visual hierarchy and layout\n- How to think about designing reusable components",
                        'duration_seconds' => 58 * 60,
                        'published_at' => '2020-02-12',
                    ],
                    [
                        'title' => 'Designing with Tailwind CSS: Utility-First Workflow',
                        'description' => 'Adam discusses the utility-first CSS workflow, when to extract components, and how Tailwind CSS changes the way you write styles.',
                        'show_notes' => "Topics covered in this episode:\n- Why utility-first\n- When to extract components\n- Working with designers",
                        'duration_seconds' => 45 * 60,
                        'published_at' => '2019-11-05',
                    ],
                    [
                        'title' => 'Refactoring UI with Steve Schoger',
                        'description' => 'Steve Schoger shares practical design tips from Refactoring UI, covering typography, color, spacing, and visual hierarchy.',
                        'show_notes' => "Steve shares insights from Refactoring UI:\n- Typography\n- Color\n- Spacing and hierarchy",
                        'duration_seconds' => 52 * 60,
                        'published_at' => '2018-12-18',
                    ],
                ],
            ],
            'syntax' => [
                'title' => 'Syntax',
                'author' => 'Wes Bos & Scott Tolinski',
                'description' => 'A Tasty Treats Podcast for Web Developers by Wes Bos and Scott Tolinski. Full of tasty web development treats covering topics like JavaScript, CSS, React, Node, and career development.',
                'website' => 'https://syntax.fm',
                'feed_url' => 'https://feeds.transistor.fm/syntax',
                'episodes' => [
                    [
                        'title' => 'React Hooks',
                        'description' => 'Wes and Scott dive deep into React Hooks, covering useState, useEffect, custom hooks, and when to use hooks vs classes.',
                        'show_notes' => "In this episode:\n- useState and useEffect\n- Custom hooks\n- Hooks vs classes",
                        'duration_seconds' => 64 * 60,
                        'published_at' => '2019-02-06',
                    ],
                    [
                        'title' => 'CSS Grid',
                        'description' => 'A complete guide to CSS Grid including grid template areas, auto-fit vs auto-fill, and practical layout examples.',
                        'show_notes' => "CSS Grid deep dive:\n- Template areas\n- auto-fit vs auto-fill\n- Practical layouts",
                        'duration_seconds' => 71 * 60,
                        'published_at' => '2018-03-21',
                    ],
                    [
                        'title' => 'TypeScript Fundamentals',
                        'description' => 'Getting started with TypeScript: types, interfaces, generics, and integrating TypeScript into your JavaScript projects.',
                        'show_notes' => "TypeScript fundamentals covered:\n- Types and interfaces\n- Generics\n- Adopting TypeScript incrementally",
                        'duration_seconds' => 68 * 60,
                        'published_at' => '2019-08-14',
                    ],
                ],
            ],
            'shop-talk-show' => [
                'title' => 'ShopTalk Show',
                'author' => 'Chris Coyier & Dave Rupert',
                'description' => 'A podcast about front end web design and development. Each week Chris Coyier and Dave Rupert are joined by a special guest to talk shop and answer listener submitted questions.',
                'website' => 'https://shoptalkshow.com',
                'feed_url' => 'https://shoptalkshow.com/feed/podcast',
                'episodes' => [
                    [
                        'title' => 'Accessibility and Web Standards',
                        'description' => 'Chris and Dave discuss web accessibility best practices, ARIA roles, semantic HTML, and building inclusive web experiences.',
                        'show_notes' => "Accessibility topics:\n- ARIA roles\n- Semantic HTML\n- Inclusive design",
                        'duration_seconds' => 55 * 60,
                        'published_at' => '2020-01-15',
                    ],
                    [
                        'title' => 'Modern CSS Layout Techniques',
                        'description' => 'Exploring modern CSS layout with Flexbox, Grid, and container queries for responsive designs.',
                        'show_notes' => "CSS layout techniques:\n- Flexbox\n- Grid\n- Container queries",
                        'duration_seconds' => 62 * 60,
                        'published_at' => '2019-09-10',
                    ],
                    [
                        'title' => 'Performance Optimization',
                        'description' => 'Tips and techniques for optimizing web performance: image optimization, lazy loading, code splitting, and measuring performance.',
                        'show_notes' => "Performance optimization strategies:\n- Image optimization\n- Lazy loading\n- Code splitting\n- Measuring",
                        'duration_seconds' => 58 * 60,
                        'published_at' => '2020-03-22',
                    ],
                ],
            ],
            'js-party' => [
                'title' => 'JS Party',
                'author' => 'Changelog Media',
                'description' => 'A community celebration of JavaScript and the web. Topics include the web platform, front-end frameworks, Node.js, web animation, SVG, robotics, IoT, and much more.',
                'website' => 'https://changelog.com/jsparty',
                'feed_url' => 'https://changelog.com/jsparty/feed',
                'episodes' => [],
            ],
            'the-changelog' => [
                'title' => 'The Changelog',
                'author' => 'Adam Stacoviak & Jerod Santo',
                'description' => 'Conversations with the hackers, leaders, and innovators of the software world. Expect in-depth interviews with the best and brightest in software engineering, open source, and leadership.',
                'website' => 'https://changelog.com/podcast',
                'feed_url' => 'https://changelog.com/podcast/feed',
                'episodes' => [],
            ],
        ];
    }
}
