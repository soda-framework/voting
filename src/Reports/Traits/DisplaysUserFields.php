<?php

namespace Soda\Voting\Reports\Traits;

trait DisplaysUserFields
{
    public function gatherUserFields($userTable)
    {
        $fields = [
            "$userTable.username as voter",
            "$userTable.email",
        ];

        foreach (config('soda.votes.reports.user_fields') as $field => $fieldName) {
            $fields[] = "$userTable.$field";
        }

        return $fields;
    }

    public function addUserFieldsToGrid($grid)
    {
        $grid->add('voter', 'Voter Name');
        $grid->add('email', 'Email');

        foreach (config('soda.votes.reports.user_fields') as $field => $fieldName) {
            $grid->add($field, $fieldName);
        }

        return $grid;
    }
}
