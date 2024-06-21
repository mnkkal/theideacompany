<?php
/**
 * Company: InfyOm Technologies, Copyright 2019, All Rights Reserved.
 *
 * User: Monika Vaghasiya
 * Email: monika.vaghasiya@infyom.com
 * Date: 11/13/2019
 * Time: 01:14 PM
 */

namespace App\Queries;

use App\Models\ZoomMeeting;
use Auth;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class MeetingDataTable.
 */
class MeetingDataTable
{
    public function get(bool $member = false, array $input = []): Builder
    {
        $query = ZoomMeeting::with('members');

        $query->when($member, function (Builder $query) {
            $query->whereHas('members', function (Builder $q) {
                $q->where('user_id', Auth::id());
            });
        });

        return $query;
    }
}
