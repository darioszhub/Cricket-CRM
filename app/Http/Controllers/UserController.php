<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Inertia\Inertia;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (string) $request->input('perPage', 50);
        $search = trim((string) $request->input('search', ''));

        if (!in_array($perPage, ['50', '100', '200', 'all'], true)) {
            $perPage = '50';
        }

        $query = Users::orderBy('IDUser');

        if ($search !== '') {
            $query->where('Username', 'like', '%' . $search . '%');
        }

        foreach (
            [
                'IDUser',
                'Username',
                'CodUserLastEdit',
                'IPADDRESS',
            ] as $field
        ) {
            $value = trim((string) $request->input($field, ''));

            if ($value !== '') {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        foreach (
            [
                'CodAgent',
                'DBSPID',
            ] as $field
        ) {
            $value = $request->input($field);

            if ($value !== null && $value !== '') {
                $query->where($field, $value);
            }
        }

        foreach (
            [
                'PowerUser',
                'Disabled',
                'LoginVisible',
                'ChangePwdFirstLogin',
            ] as $field
        ) {
            $value = $request->input($field);

            if ($value !== null && $value !== '') {
                $query->where($field, (int) $value);
            }
        }

        $dateFilters = [
            'DBLOGINTIME' => [
                'from' => 'DBLOGINTIME_FROM',
                'to' => 'DBLOGINTIME_TO',
            ],
            'TimestampLastLogin' => [
                'from' => 'TimestampLastLogin_FROM',
                'to' => 'TimestampLastLogin_TO',
            ],
            'TimestampINS' => [
                'from' => 'TimestampINS_FROM',
                'to' => 'TimestampINS_TO',
            ],
            'TimestampEDT' => [
                'from' => 'TimestampEDT_FROM',
                'to' => 'TimestampEDT_TO',
            ],
        ];

        foreach ($dateFilters as $column => $range) {
            $from = $request->input($range['from']);
            $to = $request->input($range['to']);

            if ($from) {
                $from = Carbon::createFromFormat('Y-m-d\TH:i', $from)
                    ->format('Ymd H:i:s');

                $query->where($column, '>=', $from);
            }

            if ($to) {
                $to = Carbon::createFromFormat('Y-m-d\TH:i', $to)
                    ->format('Ymd H:i:s');

                $query->where($column, '<=', $to);
            }
        }

        if ($perPage === 'all') {
            $collection = $query->get();

            $users = [
                'data' => $collection,
                'from' => $collection->isEmpty() ? null : 1,
                'to' => $collection->count(),
                'total' => $collection->count(),
                'links' => [],
            ];
        } else {
            $users = $query
                ->paginate((int) $perPage)
                ->withQueryString()
                ->toArray();
        }

        return Inertia::render('Users/Index', [
            'users' => $users,
            'perPage' => $perPage,
            'search' => $search,
        ]);
    }
}
