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

    public function addUserFieldsToGrid($grid, $orderableColumns = [])
    {
        $grid->add('voter', 'Voter Name', in_array('voter', $orderableColumns) ? true : false);
        $grid->add('email', 'Email', in_array('email', $orderableColumns) ? true : false);

        foreach (config('soda.votes.reports.user_fields') as $field => $fieldName) {
            $grid->add($field, $fieldName, in_array($field, $orderableColumns) ? true : false);
        }

        return $grid;
    }
}
