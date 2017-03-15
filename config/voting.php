<?php

return [
  'max_votes_per_category'  => 1,
  'replace_votes'           => true,
  'hint'                    => 'soda.votes.voting',
  'api_path'                => 'voting/api/v0',
  'fields'                  => [
      'nominee' => [
          'name' => [
              'type'   => 'text',
              'label'  => 'Nominee Name',
              'filter' => [
                  'enabled' => true,
                  'type'    => 'text',
              ],
              'grid' => [
                  'enabled'  => true,
                  'sortable' => true,
              ],
              'rules' => 'required|max:128',
          ],
          'description' => [
              'type'  => 'textarea',
              'label' => 'Nominee Description',
              'grid'  => [
                  'enabled' => true,
              ],
              'rules' => 'max:255',
          ],
          'image' => [
              'type'  => 'fancyupload',
              'label' => 'Nominee Image',
              'grid'  => [
                  'enabled' => true,
              ],
              'rules' => 'required|max:128',
          ],
          'details' => [
              'type'  => 'tinymce',
              'label' => 'Nominee Details',
              'rules' => 'max:1000',
          ],
      ],
  ],
];
