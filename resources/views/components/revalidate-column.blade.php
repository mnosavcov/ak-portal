@props(['column', 'yesNo' => false])

<template x-if="proxyData.usersOrigin[user.id].check_status === 're_verified'">
    <div
         x-data="{yesNo: @js($yesNo), column: @js($column), dataUser: JSON.parse(user.last_verified_data)}"
         class="block font-Spartan-Bold text-[11px] tablet:text-[13px] leading-29px text-app-orange"
         x-text="yesNo ? (parseInt(dataUser[column]) === 1 ? 'ANO' : 'NE') : dataUser[column]">
    </div>
</template>
