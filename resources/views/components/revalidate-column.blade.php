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
                },
                colorX(column) {
                    let textOld = this.dataUser[column];
                    textOld = (textOld === null ? '' : textOld);
                    textOld = String(textOld).trim();

                    let textActual = proxyData.usersOrigin[user.id][column];
                    textActual = (textActual === null ? '' : textActual);
                    textActual = String(textActual).trim();

                    if(textOld !== textActual) {
                        return 'text-app-red';
                    }

                    return 'text-app-orange';
                }
            }"
         class="block font-Spartan-Bold text-[11px] tablet:text-[13px] leading-29px"
         :class="colorX(column)"
         x-html="textX(column) === '' && colorX(column) === 'text-app-red' ? '&lt; empty &gt;' : textX(column)">
    </div>
</template>
