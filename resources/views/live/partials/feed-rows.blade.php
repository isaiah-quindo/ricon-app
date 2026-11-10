@use('App\Support\LiveTracking\Format')

{{-- Rows of the latest arrivals table, already filtered and limited by the server. Re-rendered by each update. --}}
@foreach ($feed as $row)
    @php $runner = $row['runner']; @endphp
    <tr>
        <td class="whitespace-nowrap px-4 py-3 align-top">
            <x-live.time :time="$row['pass']['in']" :now="$race->now" />
        </td>
        <td class="px-4 py-3 align-top">
            <a href="{{ route('live.runner', [$race->course['slug'], $runner['bib']]) }}"
                class="group -my-1 flex min-h-[44px] flex-col justify-center rounded-md py-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">
                <span class="font-semibold text-white underline-offset-2 group-hover:text-orange-400 group-hover:underline">{{ $runner['name'] }}</span>
                <x-live.bib-chip :bib="$runner['bib']" class="mt-1 self-start" />
            </a>
        </td>
        <td class="px-4 py-3 align-top">
            <span class="block text-white">{{ $row['cp']['display'] }}</span>
            <span class="block font-mono text-xs text-gray-400">{{ $row['cp']['label'] }} · {{ Format::km($row['cp']['km']) }}</span>
        </td>
        <td class="whitespace-nowrap px-4 py-3 align-top">
            <span class="block font-mono text-white">{{ Format::ordinal($row['pass']['rank']) }}</span>
            <x-live.rank-change :change="$row['pass']['rank_change']" />
        </td>
        <td class="px-4 py-3 align-top">
            <x-live.status-pill :state="$row['state']" />
        </td>
    </tr>
@endforeach
