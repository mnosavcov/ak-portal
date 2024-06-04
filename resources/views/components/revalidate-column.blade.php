@props(['column', 'yesNo' => false, 'br' => false])

<template x-if="proxyData.usersOrigin[user.id].check_status === 're_verified'">
    <div
         x-data="{
                yesNo: @js($yesNo),
                br: @js($br),
                column: @js($column),
                dataUser: JSON.parse(user.last_verified_data),
                textX(column) {
                    let text = this.dataUser[column];
                    text = (text === null ? '' : text);
                    text = String(text).trim();

                    if(this.yesNo) {
                        text = (parseInt(text) === 1 ? 'ANO' : 'NE');
                    } else if(this.br) {
                        text = text.replace(/\n/g, '<br>');
                    }

                    return text
                }
            }"
         class="block font-Spartan-Bold text-[11px] tablet:text-[13px] leading-29px text-app-orange"
         x-html="textX(column)">
    </div>
</template>
