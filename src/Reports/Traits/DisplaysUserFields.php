<?php

namespace Soda\Voting\Reports\Traits;

/**
 * Class CategoryVoteReport.
 *
 * Generates a report of votes per nominee, listing total votes and number of voters
 */
trait DisplaysUserFields
{
    public function gatherUserFields($userTable)
    {
        $userFields = [
            "$userTable.username as name",
            "$userTable.email",
        ];

        foreach (config('soda.votes.reports.user_fields') as $userField => $fieldName) {
            $userFields[] = "$userTable.$userField";
        }

        return $userFields;
    }

    public function addUserFieldsToGrid($grid)
    {
        $grid->add('name', 'Name');
        $grid->add('email', 'Email');

        foreach (config('soda.votes.reports.user_fields') as $userField => $fieldName) {
            $grid->add($userField, $fieldName);
        }

        return $grid;
    }
}
