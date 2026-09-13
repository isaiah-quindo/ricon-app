@extends('layouts.public')
@section('title', 'TGC100 Grant')
@section('og_title', 'TGC100 Grant — RICON')
@section('og_description', "The TGC100 Grant is RiCON's commitment to supporting one runner taking on The Great Cordillera 100KM. Registration, accommodation, and race-week allowances, covered.")

@section('content')

{{-- ========================================================
         HERO
    ======================================================== --}}
<section id="overview" class="relative overflow-hidden pt-16">
    <div class="absolute inset-0 select-none" style="background-color:#0d0d0d; background-image:
        radial-gradient(ellipse 75% 110% at 0% 55%, rgba(234,88,12,0.35), transparent 62%),
        radial-gradient(ellipse 55% 65% at 100% 12%, rgba(249,115,22,0.25), transparent 58%),
        radial-gradient(ellipse 65% 80% at 98% 88%, rgba(194,65,12,0.25), transparent 60%);"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

    <div class="relative z-10 mx-auto px-8 py-16 lg:py-24" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-3">Applications Closed</p>
        <h1 class="text-5xl md:text-6xl xl:text-7xl font-black text-white leading-none mb-5">
            TGC<span class="text-orange-500">100</span> Grant
        </h1>
        <div class="w-40 h-1 bg-orange-500 rounded-full mb-6"></div>
        <p class="text-gray-300 text-lg max-w-2xl mb-3 leading-relaxed">The TGC100 Grant is RiCON's commitment to supporting one runner taking on The Great Cordillera 100KM.</p>
        <p class="text-gray-300 text-lg max-w-2xl mb-3 leading-relaxed">We cover race registration, accommodation, and selected race-week expenses. You bring the training, the commitment, and the reason you're willing to take on 100 kilometers.</p>
        <p class="text-gray-500 text-lg max-w-2xl mb-12">More than a sponsorship, this is our way of investing in the people and stories that move Philippine trail running forward.</p>

        <div class="flex flex-wrap gap-10 mb-14">
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold mb-1">Race Date</p>
                <p class="text-white font-black text-2xl">Nov 13, 2026</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold mb-1">Distance</p>
                <p class="text-white font-black text-2xl">100<span class="text-base text-gray-400 font-bold ml-1">KM</span></p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold mb-1">Elevation Gain</p>
                <p class="text-white font-black text-2xl">7,000<span class="text-base text-gray-400 font-bold ml-1">M D+</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 border-t border-white/10 divide-y sm:divide-y-0 sm:divide-x divide-white/10">
            <div class="pt-6 sm:pr-8">
                <p class="text-orange-500 text-[11px] font-bold uppercase tracking-wider mb-2">Applications</p>
                <p class="text-white font-bold">September 7-13</p>
            </div>
            <div class="pt-6 sm:px-8">
                <p class="text-orange-500 text-[11px] font-bold uppercase tracking-wider mb-2">Interview Invite</p>
                <p class="text-white font-bold">2-3 Business Days After Applying</p>
            </div>
            <div class="pt-6 sm:pl-8">
                <p class="text-orange-500 text-[11px] font-bold uppercase tracking-wider mb-2">Grantee Announced</p>
                <p class="text-white font-bold">September 18</p>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================
         QUICK NAV
    ======================================================== --}}
<div class="bg-[#111111] border-b border-white/10 sticky top-16 z-40">
    <div class="mx-auto px-8 overflow-x-auto" style="max-width:1280px;">
        <div class="flex items-center gap-6 h-12 text-lg whitespace-nowrap">
            <a href="#overview" class="text-gray-400 hover:text-white transition-colors">Overview</a>
            <a href="#coverage" class="text-gray-400 hover:text-white transition-colors">Coverage</a>
            <a href="#mechanics" class="text-gray-400 hover:text-white transition-colors">Process</a>
            <a href="#apply" class="text-gray-400 hover:text-white transition-colors">Apply</a>
            <a href="#faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a>
            <a href="#terms" class="text-gray-400 hover:text-white transition-colors">Terms</a>
        </div>
    </div>
</div>

{{-- ========================================================
         COVERAGE
    ======================================================== --}}
<section id="coverage" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Support</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">What's Covered</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">The TGC100 Grant removes the financial barrier between a committed runner and the start line of The Great Cordillera 100.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['icon' => 'M3 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4Z|M13 5v2M13 11v2M13 17v2', 'title' => 'Registration', 'desc' => 'Full entry fee for TGC 100KM, covered.', 'amount' => '100%'],
                ['icon' => 'M2 20v-7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v7|M2 13V8a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v3|M2 20h20', 'title' => 'Accommodation', 'desc' => 'Lodging for race weekend, arranged by RiCON.', 'amount' => 'Covered'],
                ['icon' => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z|M14.5 9.5l-2 5-5 2 2-5 5-2Z', 'title' => 'Transpo Allowance', 'desc' => 'Toward travel to and from the race venue.', 'amount' => '₱1,500'],
                ['icon' => 'M6 8V6a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2|M4 8h16v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8Z|M4 13h16', 'title' => 'Misc. Allowance', 'desc' => 'Toward race-week and participation-related expenses.', 'amount' => '₱1,500'],
            ] as $card)
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-6 hover:border-white/20 transition-colors">
                <div class="w-10 h-10 rounded-lg bg-orange-500/10 text-orange-500 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        @foreach(explode('|', $card['icon']) as $path)
                        <path d="{{ $path }}"/>
                        @endforeach
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg mb-2">{{ $card['title'] }}</h3>
                <p class="text-gray-400 text-lg mb-5 min-h-[3rem]">{{ $card['desc'] }}</p>
                <p class="text-white font-black text-2xl">{{ $card['amount'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         MECHANICS
    ======================================================== --}}
<section id="mechanics" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Timeline</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Mechanics: How the Process Works</h2>
        <p class="text-gray-400 max-w-2xl mb-12 leading-relaxed">Three stages. Straightforward by design. We'd rather you spend the time training than chasing paperwork.</p>

        <div class="space-y-4">
            @foreach([
                ['num' => '01', 'meta' => 'Sep 7-13', 'title' => 'Application Window', 'text' => 'Submit the application form, including your questionnaire responses. Applications are reviewed on a rolling basis, so applying early gives your interview slot more room, but the window stays open through September 13, 11:59 PM PHT.'],
                ['num' => '02', 'meta' => '+2-3 Days', 'title' => 'Interview Invitation', 'text' => 'Shortlisted applicants receive an email invitation within 2-3 days of submitting their application. The interview is a short conversation, not a re-test. It\'s where we get to know the person behind the answers. Check your spam folder, emails come from RiCON\'s official address.'],
                ['num' => '03', 'meta' => 'Sep 18', 'title' => 'Grantee Announced', 'text' => 'The final grantee is announced September 18 via email and on RiCON\'s official channels. All applicants are notified of the outcome, whether selected or not.'],
            ] as $stage)
            <div class="bg-[#0d0d0d] border border-white/10 rounded-2xl p-6 md:p-7">
                <div class="flex flex-col md:flex-row md:items-start gap-4 md:gap-8">
                    <div class="flex-none md:w-32">
                        <span class="block font-black text-3xl text-white mb-1">{{ $stage['num'] }}</span>
                        <span class="text-orange-500 text-xs font-bold uppercase tracking-wider">{{ $stage['meta'] }}</span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-1.5">{{ $stage['title'] }}</h3>
                        <p class="text-gray-400 text-lg leading-relaxed max-w-2xl">{{ $stage['text'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         APPLICATION FORM (closed)
    ======================================================== --}}
<section id="apply" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Application</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">TGC100 Grant Application Form</h2>
        <p class="text-gray-400 mb-10 max-w-2xl leading-relaxed">Applications for the TGC100 Grant have closed.</p>

        <div class="bg-[#111111] border border-white/10 rounded-2xl p-9 md:p-12 text-center">
            <div class="w-12 h-12 rounded-full bg-orange-500/10 text-orange-500 flex items-center justify-center mx-auto mb-5 text-xl font-black">&times;</div>
            <h3 class="text-white font-bold text-xl mb-2">Grant Submissions Have Ended</h3>
            <p class="text-gray-400 max-w-md mx-auto text-lg leading-relaxed">The TGC100 Grant application window closed on September 13. We're no longer accepting new applications for this cycle. The grantee will be announced September 18 via email and RiCON's official channels.</p>
        </div>
    </div>
</section>

{{-- ========================================================
         FAQ
    ======================================================== --}}
<section id="faq" class="bg-[#111111] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="text-center mb-12">
            <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-3">Questions</p>
            <h2 class="text-3xl md:text-4xl font-bold text-white">FAQ: Before You Apply</h2>
        </div>

        <div class="space-y-3" x-data="{ open: null }">
            @foreach([
                ['Who can apply?', 'Any runner intending to race The Great Cordillera 100KM who meets the race\'s own entry requirements. There is no elite-performance minimum. Grants are evaluated across all criteria, not on speed alone.'],
                ['What happens after I\'m selected?', 'The grantee will be onboarded with race logistics, media commitments, and disbursement details for the transportation and miscellaneous allowances. Full obligations are outlined in the Terms & Conditions.'],
                ['Do I need to already be registered for TGC100?', 'No. Registration is part of what the grant covers. Do not pay for your own registration in anticipation of the grant. RiCON will process it directly for the confirmed grantee.'],
            ] as $i => [$q, $a])
            <div class="bg-[#0d0d0d] rounded-xl overflow-hidden">
                <button type="button" @click="open = open === {{ $i }} ? null : {{ $i }}"
                    class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left">
                    <span class="text-white font-semibold text-lg md:text-xl">{{ $q }}</span>
                    <svg class="w-4 h-4 text-orange-500 flex-shrink-0 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
                    :class="open === {{ $i }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                    <div class="overflow-hidden">
                        <p class="text-gray-400 text-lg leading-relaxed px-6 pb-5">{{ $a }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================================
         TERMS & CONDITIONS
    ======================================================== --}}
<section id="terms" class="bg-[#0d0d0d] py-20">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <p class="text-orange-500 text-lg font-semibold uppercase tracking-wider mb-2">Effective for the 2026 Application Cycle</p>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">TGC100 Grant: Terms &amp; Conditions</h2>
        <p class="text-gray-400 mb-10 max-w-2xl leading-relaxed">By submitting an application for the TGC100 Grant, you agree to the terms below.</p>

        <div class="space-y-3" x-data="{ open: null }">
            @php
            $terms = [
                [
                    'title' => '1. Eligibility',
                    'text' => 'To apply for the TGC100 Grant, an applicant must be at least 18 years old at the time of application, or provide parental/guardian consent if between 16 and 17 years old; intend to compete in The Great Cordillera 100KM (November 13, 2026) and meet that race\'s own entry and qualification requirements; not have received a TGC100 Grant or equivalent full-coverage RiCON grant in the two most recent grant cycles; submit truthful, complete information, as RiCON reserves the right to verify any claim made in an application, including race history, community contributions, and financial disclosures; and not be a current employee, contractor, or immediate family member of RiCON staff directly involved in grant selection. Employees, contractors, and immediate family members of RiCON who are involved in the review or selection process are ineligible to apply in the cycle they participate in reviewing.',
                ],
                [
                    'title' => '2. Application & Selection Process',
                    'text' => 'The application window is September 7-13, 2026, closing 11:59 PM Philippine Time. Interview invitations are sent within approximately 2-3 business days of application submission via email, and RiCON is not responsible for invitations lost to spam filters, incorrect email addresses, or full inboxes. The grantee is announced September 18, 2026, when all applicants, selected and not selected, will be notified by email. These dates may shift due to circumstances outside RiCON\'s reasonable control, and any change will be posted on RiCON\'s official channels. Applications are evaluated by a review panel against five criteria: performance, dedication, and respect for the sport; impact to the community; personal development (why you run); relevance to TGC specifically; and financial need. No single criterion is weighted to guarantee selection on its own. Shortlisted applicants will be invited to a short interview (in person or video call) as part of final selection. Failure to respond to an interview invitation within the timeframe stated in the invitation email may result in disqualification from that cycle.',
                ],
                [
                    'title' => '3. Grant Coverage & Disbursement',
                    'text' => 'The TGC100 Grant covers race registration for TGC 100KM (arranged and paid directly by RiCON, the grantee must not pre-pay their own registration in anticipation of selection), accommodation for race weekend (arranged by RiCON), a transportation allowance of ₱1,500, and a miscellaneous allowance of ₱1,500, both disbursed ahead of race weekend. The grant does not cover gear, nutrition products, personal insurance, travel outside the immediate race weekend, or any costs beyond what is explicitly listed above. Allowances are disbursed once, are non-recurring, and are contingent on the grantee completing onboarding requirements set by RiCON.',
                ],
                [
                    'title' => '4. Grantee Obligations',
                    'text' => 'By accepting the TGC100 Grant, the grantee agrees to start and make a genuine, good-faith attempt to complete TGC 100KM (a DNF from injury, medical withdrawal, or legitimate race-day circumstance does not violate this obligation and is never treated as a failure of the grant); complete onboarding and respond to RiCON communications in a timely manner; participate in the media and documentation activities described in Section 5; represent the grant, RiCON, and the running community consistent with the Code of Conduct in Section 9; and not transfer, sell, or assign their grant status or associated benefits.',
                ],
                [
                    'title' => '5. Media, Documentation & RiCON Official Media Partners',
                    'text' => 'As a condition of accepting the TGC100 Grant, the grantee consents to be photographed, filmed, interviewed, and otherwise documented by RiCON before, during, and after TGC100 race weekend, for use across RiCON\'s owned channels and by RiCON\'s Official Media Partners. "RiCON Official Media Partners" means third-party media outlets, content creators, photographers, videographers, and publications formally accredited by RiCON to cover TGC100; the current list is available on request from partnership@ricon.ph and may change between cycles. Footage, photography, quotes, and story material gathered under this consent may be used in race recaps, brand and grant-program marketing, Official Media Partner publications and broadcasts, and archival or documentary content, without additional compensation to the grantee beyond the grant itself, unless a separate paid engagement is agreed in writing. RiCON commits to representing the grantee, including those who DNF, finish mid-pack, or finish at the back of the field, with the same respect and craft as elite finishers, and a DNF is never framed as a failure in RiCON or Official Media Partner content produced under this program. The grantee may request, in writing, that specific sensitive personal details be withheld from published content. RiCON will make reasonable efforts to honor such requests but cannot guarantee control over Official Media Partner editorial decisions once material has been shared under accreditation, and this media consent cannot be revoked retroactively for content already published.',
                ],
                [
                    'title' => '6. Withdrawal, Forfeiture & Replacement',
                    'text' => 'If the grantee withdraws before race weekend, they must notify RiCON in writing as soon as reasonably possible, and unused allowances must be returned if already disbursed and the grantee does not participate in any covered activity. RiCON reserves the right to revoke a grant, at its discretion, if the grantee provided materially false information, violates the Code of Conduct, or fails to complete onboarding without reasonable cause. RiCON may, at its discretion, offer a forfeited grant slot to a waitlisted applicant from the same cycle. If the grantee does not start TGC100 for a documented medical or emergency reason, they are not required to return disbursed allowances already spent on race-related preparation, at RiCON\'s discretion.',
                ],
                [
                    'title' => '7. Data Privacy',
                    'text' => 'Information submitted in the application, including financial disclosures, is used solely for grant evaluation, onboarding, and program administration, and is accessible only to the review panel and relevant RiCON staff. RiCON handles personal data in accordance with the Philippine Data Privacy Act of 2012 (RA 10173). Applicants may request access to, correction of, or deletion of their submitted data by writing to partnership@ricon.ph, subject to records RiCON is required to retain for program integrity. Financial-need responses are read only by the review panel and are not shared with Official Media Partners or published in any form.',
                ],
                [
                    'title' => '8. Liability & Assumption of Risk',
                    'text' => 'Ultra-distance trail running carries inherent physical risk, including but not limited to injury, altitude effects, exposure, and terrain hazards. The grantee acknowledges this risk independently of and in addition to any waiver required by the TGC100 race organizer, and agrees that RiCON\'s provision of a grant does not constitute a guarantee of safety, performance outcome, or fitness assessment. RiCON is not liable for injury, loss, or damages arising from a grantee\'s participation in TGC100 or related grant activities, except where caused by RiCON\'s own gross negligence or willful misconduct. The grantee is responsible for their own health clearance, travel insurance (if desired), and personal gear beyond what is explicitly covered under Section 3.',
                ],
                [
                    'title' => '9. Code of Conduct',
                    'text' => 'The grantee is expected to uphold the same standard RiCON holds itself to: respect for the mountains, the host communities, race officials, volunteers, and fellow runners at every level of the pack. Conduct that includes harassment, discrimination, dishonesty about race results, or disrespect toward host communities or event staff may result in immediate forfeiture of the grant and exclusion from future RiCON grant cycles.',
                ],
                [
                    'title' => '10. Discretion of RiCON',
                    'text' => 'All decisions relating to eligibility, selection, interview shortlisting, grant amounts, and forfeiture are made at RiCON\'s sole discretion and are final. RiCON is not obligated to disclose individual scoring, review notes, or reasons for non-selection, though general feedback may be offered where practical.',
                ],
                [
                    'title' => '11. General Provisions',
                    'text' => 'These Terms are governed by the laws of the Republic of the Philippines. RiCON may update these Terms between grant cycles, and the version in effect at the time of application governs that cycle. If any provision of these Terms is found unenforceable, the remaining provisions continue in full force. These Terms, together with the application form, constitute the entire agreement between RiCON and the applicant regarding the TGC100 Grant.',
                ],
                [
                    'title' => '12. Contact',
                    'text' => 'Questions about these Terms or the TGC100 Grant program can be directed to partnership@ricon.ph.',
                ],
            ];
            @endphp

            @foreach($terms as $index => $term)
            <div class="bg-[#111111] border border-white/10 rounded-2xl overflow-hidden">
                <button type="button" @click="open = open === {{ $index }} ? null : {{ $index }}"
                    class="w-full flex items-start gap-4 p-6 text-left">
                    <div class="w-7 h-7 rounded-full bg-orange-500/10 text-orange-500 text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $index + 1 }}</div>
                    <h3 class="flex-1 text-white font-semibold text-lg">{{ $term['title'] }}</h3>
                    <svg class="w-4 h-4 text-orange-500 flex-shrink-0 mt-0.5 transition-transform" :class="open === {{ $index }} ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="grid transition-[grid-template-rows] duration-300 ease-in-out"
                    :class="open === {{ $index }} ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                    <div class="overflow-hidden">
                        <p class="text-gray-400 text-lg leading-relaxed pl-11 pr-6 pb-6">{{ $term['text'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
