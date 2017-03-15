<?php

namespace Soda\Voting\Reports\Traits;

trait DisplaysNomineeFields
{
    public function gatherNomineeFields($nomineesTable)
    {
        $nomineeFields = [];

        foreach (config('soda.votes.reports.nominee_fields') as $field => $fieldName) {
            $nomineeFields[] = "$nomineesTable.$field";
        }

        return $nomineeFields;
    }

    public function addNomineeFieldsToGrid($grid)
    {
        foreach (config('soda.votes.reports.nominee_fields') as $field => $fieldName) {
            $grid->add($field, $fieldName);
        }

        return $grid;
    }
}
