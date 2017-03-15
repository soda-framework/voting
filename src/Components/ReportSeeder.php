<?php

namespace Soda\Voting\Components;

use Soda\Cms\Models\Role;
use Soda\Cms\Models\Field;
use Soda\Reports\Models\Report;
use Soda\Voting\Models\Category;
use Soda\Voting\Reports\UserVotes;
use Soda\Voting\Reports\UniqueUsers;
use Soda\Voting\Reports\UserEntries;
use Soda\Voting\Reports\CategoryVoteReport;
use Illuminate\Database\Seeder as BaseSeeder;

class ReportSeeder extends BaseSeeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $categoryIdField = Field::create([
            'name'         => 'Category',
            'field_name'   => 'category_id',
            'field_type'   => 'relationship',
            'field_params' => [
                'model'        => Category::class,
                'value_column' => 'name',
            ],
            'show_in_table' => 0,
        ]);

        $categoryVoteReport = Report::create([
            'name'        => 'Votes Per Category',
            'description' => 'Votes per nominee, listing total votes and number of voters.',
            'class'       => CategoryVoteReport::class,
        ]);

        $uniqueUsersReport = Report::create([
            'name'        => 'Unique Users',
            'description' => 'List of all unique users that have voted',
            'class'       => UniqueUsers::class,
        ]);

        $userEntriesReport = Report::create([
            'name'        => 'User Entries',
            'description' => 'List of all entries submitted by all users (an entry may consist of one or more votes)',
            'class'       => UserEntries::class,
        ]);

        $userVotesReport = Report::create([
            'name'        => 'User Votes',
            'description' => 'List of all singular votes submitted by all users',
            'class'       => UserVotes::class,
        ]);

        $adminRole = Role::whereName('admin')->first();

        if ($adminRole) {
            $categoryVoteReport->fields()->attach($categoryIdField, ['position' => 0]);
            $categoryVoteReport->roles()->attach($adminRole);
            $uniqueUsersReport->roles()->attach($adminRole);
            $userEntriesReport->roles()->attach($adminRole);
            $userVotesReport->roles()->attach($adminRole);
        }
    }
}
