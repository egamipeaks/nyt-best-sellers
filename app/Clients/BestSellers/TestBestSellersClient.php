<?php

namespace App\Clients\BestSellers;

use App\Contracts\BestSellersClientContract;

class TestBestSellersClient implements BestSellersClientContract
{
    public function getBestSellers(array $params): array
    {
        $results = $this->getFakeResults(20);

        return [
            'status' => 'OK',
            'copyright' => 'Copyright (c) 2019 The New York Times Company. All Rights Reserved.',
            'num_results' => count($results),
            'results' => $results,
        ];
    }

    private function getFakeResults(int $resultCount)
    {
        $results = [];

        foreach (range(1, $resultCount) as $i) {
            $results[] = [
                'title' => strtoupper(fake()->sentence(3)),
                'description' => fake()->text(100),
                'contributor' => 'by '.fake()->name,
                'author' => fake()->name,
                'contributor_note' => '',
                'price' => (string) fake()->randomFloat(2, 10, 100),
                'age_group' => '',
                'publisher' => fake()->company,
                'isbns' => [
                    [
                        'isbn10' => fake()->isbn10,
                        'isbn13' => fake()->isbn13,
                    ],
                ],
                'ranks_history' => [
                    [
                        'primary_isbn10' => fake()->isbn10,
                        'primary_isbn13' => fake()->isbn13,
                        'rank' => fake()->numberBetween(1, 100),
                        'list_name' => fake()->words(2, true),
                        'display_name' => fake()->word,
                        'published_date' => fake()->date,
                        'bestsellers_date' => fake()->date,
                        'weeks_on_list' => fake()->numberBetween(0, 10),
                        'ranks_last_week' => null,
                        'asterisk' => fake()->randomElement([0, 1]),
                        'dagger' => fake()->randomElement([0, 1]),
                    ],
                ],
                'reviews' => [
                    [
                        'book_review_link' => '',
                        'first_chapter_link' => '',
                        'sunday_review_link' => '',
                        'article_chapter_link' => '',
                    ],
                ],
            ];
        }

        return $results;
    }
}
