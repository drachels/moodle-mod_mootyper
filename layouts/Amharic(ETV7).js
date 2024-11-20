/**
 * @fileOverview Amharic(ETV7) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 7.0
 * @since 20240930
 */

/**
 * Check for combined character.
 * @param {string} chr The combined character.
 * @returns {string} The character.
 */
function isCombined(chr) {
    return (chr === '´' || chr === '`' || chr === '~');
}

/**
 * Process keyup for combined character.
 * @param {string} e The combined character.
 * @returns {bolean} The result.
 */
function keyupCombined(e) {
    return false;
}

/**
 * Check for character requiring a sequence of keys.
 * @param {string} chr The character being checked
 * @returns {boolean} True if the character requires a sequence of keys to be entered
 */
function isCharSequence(chr) {
    const sequences = defineSequences();
    return (Object.keys(sequences).indexOf(chr) >= 0);
}

/**
 * Returns an array of characters that will be output when entering 
 * the keystrokes for the given character.
 * @param {string} chr The target character.
 * @returns {Array} The interim characters.
 */
function getSequence(chr) {
    console.log("Getting sequence for ", chr);
    const sequences = defineSequences();
    return sequences[chr];
}

function defineSequences() {
    const sequences = {
    "ሀ": ["ህ", "ሀ"],    // key sequence: he
    "ሁ": ["ህ", "ሁ"],   // key sequence: hu
    "ሂ": ["ህ", "ሂ"],    // key sequence: hi
    "ሃ": ["ህ", "ሃ"],    // key sequence: ha
    "ሄ": ["ህ", "ሂ", "ሄ"],    // key sequence: hie
    "ሆ": ["ህ", "ሆ"],    // key sequence: ho
    "ለ": ["ል", "ለ"],    // key sequence: le
    "ሉ": ["ል", "ሉ"],   // key sequence: lu
    "ሊ": ["ል", "ሊ"],    // key sequence: li
    "ላ": ["ል", "ላ"],    // key sequence: la
    "ሌ": ["ል", "ሊ", "ሌ"],    // key sequence: lie
    "ሎ": ["ል", "ሎ"],    // key sequence: lo
    "ሐ": ["ህ", "ሕ", "ሐ"],    // key sequence: hhe
    "ሑ": ["ህ", "ሕ", "ሑ"],   // key sequence: hhu
    "ሒ": ["ህ", "ሕ", "ሒ"],    // key sequence: hhi
    "ሓ": ["ህ", "ሕ", "ሓ"],    // key sequence: hha
    "ሔ": ["ህ", "ሕ", "ሒ", "ሔ"],    // key sequence: hhie
    "ሕ": ["ህ", "ሕ"],    // key sequence: hh
    "ሖ": ["ህ", "ሕ", "ሖ"],    // key sequence: hho
    "መ": ["ም", "መ"],    // key sequence: me
    "ሙ": ["ም", "ሙ"],   // key sequence: mu
    "ሚ": ["ም", "ሚ"],    // key sequence: mi
    "ማ": ["ም", "ማ"],    // key sequence: ma
    "ሜ": ["ም", "ሚ", "ሜ"],    // key sequence: mie
    "ሞ": ["ም", "ሞ"],    // key sequence: mo
    "ሠ": ["ስ", "ሥ", "ሠ"],    // key sequence: sse
    "ሡ": ["ስ", "ሥ", "ሡ"],   // key sequence: ssu
    "ሢ": ["ስ", "ሥ", "ሢ"],    // key sequence: ssi
    "ሣ": ["ስ", "ሥ", "ሣ"],    // key sequence: ssa
    "ሤ": ["ስ", "ሥ", "ሢ", "ሤ"],    // key sequence: ssie
    "ሥ": ["ስ", "ሥ"],    // key sequence: ss
    "ሦ": ["ስ", "ሥ", "ሦ"],    // key sequence: sso
    "ረ": ["ር", "ረ"],    // key sequence: re
    "ሩ": ["ር", "ሩ"],   // key sequence: ru
    "ሪ": ["ር", "ሪ"],    // key sequence: ri
    "ራ": ["ር", "ራ"],    // key sequence: ra
    "ሬ": ["ር", "ሪ", "ሬ"],    // key sequence: rie
    "ሮ": ["ር", "ሮ"],    // key sequence: ro
    "ሰ": ["ስ", "ሰ"],    // key sequence: se
    "ሱ": ["ስ", "ሱ"],   // key sequence: su
    "ሲ": ["ስ", "ሲ"],    // key sequence: si
    "ሳ": ["ስ", "ሳ"],    // key sequence: sa
    "ሴ": ["ስ", "ሲ", "ሴ"],    // key sequence: sie
    "ሶ": ["ስ", "ሶ"],    // key sequence: so
    "ሸ": ["ሽ", "ሸ"],    // key sequence: xe
    "ሹ": ["ሽ", "ሹ"],   // key sequence: xu
    "ሺ": ["ሽ", "ሺ"],    // key sequence: xi
    "ሻ": ["ሽ", "ሻ"],    // key sequence: xa
    "ሼ": ["ሽ", "ሺ", "ሼ"],    // key sequence: xie
    "ሾ": ["ሽ", "ሾ"],    // key sequence: xo
    "ቀ": ["ቅ", "ቀ"],    // key sequence: qe
    "ቁ": ["ቅ", "ቁ"],   // key sequence: qu
    "ቂ": ["ቅ", "ቂ"],    // key sequence: qi
    "ቃ": ["ቅ", "ቃ"],    // key sequence: qa
    "ቄ": ["ቅ", "ቂ", "ቄ"],    // key sequence: qie
    "ቆ": ["ቅ", "ቆ"],    // key sequence: qo
    "በ": ["ብ", "በ"],    // key sequence: be
    "ቡ": ["ብ", "ቡ"],   // key sequence: bu
    "ቢ": ["ብ", "ቢ"],    // key sequence: bi
    "ባ": ["ብ", "ባ"],    // key sequence: ba
    "ቤ": ["ብ", "ቢ", "ቤ"],    // key sequence: bie
    "ቦ": ["ብ", "ቦ"],    // key sequence: bo
    "ተ": ["ት", "ተ"],    // key sequence: te
    "ቱ": ["ት", "ቱ"],   // key sequence: tu
    "ቲ": ["ት", "ቲ"],    // key sequence: ti
    "ታ": ["ት", "ታ"],    // key sequence: ta
    "ቴ": ["ት", "ቲ", "ቴ"],    // key sequence: tie
    "ቶ": ["ት", "ቶ"],    // key sequence: to
    "ቸ": ["ች", "ቸ"],    // key sequence: ce
    "ቹ": ["ች", "ቹ"],   // key sequence: cu
    "ቺ": ["ች", "ቺ"],    // key sequence: ci
    "ቻ": ["ች", "ቻ"],    // key sequence: ca
    "ቼ": ["ች", "ቺ", "ቼ"],    // key sequence: cie
    "ቾ": ["ች", "ቾ"],    // key sequence: co
    "ኀ": ["ህ", "ሕ", "ኅ", "ኀ"],    // key sequence: hhhe
    "ኁ": ["ህ", "ሕ", "ኅ", "ኁ"],   // key sequence: hhhu
    "ኂ": ["ህ", "ሕ", "ኅ", "ኂ"],    // key sequence: hhhi
    "ኃ": ["ህ", "ሕ", "ኅ", "ኃ"],    // key sequence: hhha
    "ኄ": ["ህ", "ሕ", "ኅ", "ኂ", "ኄ"],    // key sequence: hhhie
    "ኅ": ["ህ", "ሕ", "ኅ"],    // key sequence: hhh
    "ኆ": ["ህ", "ሕ", "ኅ", "ኆ"],    // key sequence: hhho
    "ነ": ["ን", "ነ"],    // key sequence: ne
    "ኑ": ["ን", "ኑ"],   // key sequence: nu
    "ኒ": ["ን", "ኒ"],    // key sequence: ni
    "ና": ["ን", "ና"],    // key sequence: na
    "ኔ": ["ን", "ኒ", "ኔ"],    // key sequence: nie
    "ኖ": ["ን", "ኖ"],    // key sequence: no
    "ኘ": ["ኝ", "ኘ"],    // key sequence: Ne
    "ኙ": ["ኝ", "ኙ"],   // key sequence: Nu
    "ኚ": ["ኝ", "ኚ"],    // key sequence: Ni
    "ኛ": ["ኝ", "ኛ"],    // key sequence: Na
    "ኜ": ["ኝ", "ኚ", "ኜ"],    // key sequence: Nie
    "ኞ": ["ኝ", "ኞ"],    // key sequence: No
    "ኣ": ["አ", "ኣ"],    // key sequence: aa
    "ኤ": ["ኢ", "ኤ"],   // key sequence: ie
    "ከ": ["ክ", "ከ"],    // key sequence: ke
    "ኩ": ["ክ", "ኩ"],   // key sequence: ku
    "ኪ": ["ክ", "ኪ"],    // key sequence: ki
    "ካ": ["ክ", "ካ"],    // key sequence: ka
    "ኬ": ["ክ", "ኪ", "ኬ"],    // key sequence: kie
    "ኮ": ["ክ", "ኮ"],    // key sequence: ko
    "ኸ": ["ኽ", "ኸ"],    // key sequence: Ke
    "ኹ": ["ኽ", "ኹ"],   // key sequence: Ku
    "ኺ": ["ኽ", "ኺ"],    // key sequence: Ki
    "ኻ": ["ኽ", "ኻ"],    // key sequence: Ka
    "ኼ": ["ኽ", "ኺ", "ኼ"],    // key sequence: Kie
    "ኾ": ["ኽ", "ኾ"],    // key sequence: Ko
    "ወ": ["ው", "ወ"],    // key sequence: we
    "ዉ": ["ው", "ዉ"],   // key sequence: wu
    "ዊ": ["ው", "ዊ"],    // key sequence: wi
    "ዋ": ["ው", "ዋ"],    // key sequence: wa
    "ዌ": ["ው", "ዊ", "ዌ"],    // key sequence: wie
    "ዎ": ["ው", "ዎ"],    // key sequence: wo
    "ዐ": ["አ", "ኣ", "ዐ"],    // key sequence: aaa
    "ዓ": ["አ", "ኣ", "ዐ", "ዓ"],   // key sequence: aaaa
    "ዔ": ["ዒ", "ዔ"],    // key sequence: Ie
    "ዕ": ["እ", "ኧ", "ዕ"],    // key sequence: eee
    "ዘ": ["ዝ", "ዘ"],    // key sequence: ze
    "ዙ": ["ዝ", "ዙ"],   // key sequence: zu
    "ዚ": ["ዝ", "ዚ"],    // key sequence: zi
    "ዛ": ["ዝ", "ዛ"],    // key sequence: za
    "ዜ": ["ዝ", "ዚ", "ዜ"],    // key sequence: zie
    "ዞ": ["ዝ", "ዞ"],    // key sequence: zo
    "ዠ": ["ዥ", "ዠ"],    // key sequence: Ze
    "ዡ": ["ዥ", "ዡ"],   // key sequence: Zu
    "ዢ": ["ዥ", "ዢ"],    // key sequence: Zi
    "ዣ": ["ዥ", "ዣ"],    // key sequence: Za
    "ዤ": ["ዥ", "ዢ", "ዤ"],    // key sequence: Zie
    "ዦ": ["ዥ", "ዦ"],    // key sequence: Zo
    "የ": ["ይ", "የ"],    // key sequence: ye
    "ዩ": ["ይ", "ዩ"],   // key sequence: yu
    "ዪ": ["ይ", "ዪ"],    // key sequence: yi
    "ያ": ["ይ", "ያ"],    // key sequence: ya
    "ዬ": ["ይ", "ዪ", "ዬ"],    // key sequence: yie
    "ዮ": ["ይ", "ዮ"],    // key sequence: yo
    "ደ": ["ድ", "ደ"],    // key sequence: de
    "ዱ": ["ድ", "ዱ"],   // key sequence: du
    "ዲ": ["ድ", "ዲ"],    // key sequence: di
    "ዳ": ["ድ", "ዳ"],    // key sequence: da
    "ዴ": ["ድ", "ዲ", "ዴ"],    // key sequence: die
    "ዶ": ["ድ", "ዶ"],    // key sequence: do
    "ጀ": ["ጅ", "ጀ"],    // key sequence: je
    "ጁ": ["ጅ", "ጁ"],   // key sequence: ju
    "ጂ": ["ጅ", "ጂ"],    // key sequence: ji
    "ጃ": ["ጅ", "ጃ"],    // key sequence: ja
    "ጄ": ["ጅ", "ጂ", "ጄ"],    // key sequence: jie
    "ጆ": ["ጅ", "ጆ"],    // key sequence: jo
    "ገ": ["ግ", "ገ"],    // key sequence: ge
    "ጉ": ["ግ", "ጉ"],   // key sequence: gu
    "ጊ": ["ግ", "ጊ"],    // key sequence: gi
    "ጋ": ["ግ", "ጋ"],    // key sequence: ga
    "ጌ": ["ግ", "ጊ", "ጌ"],    // key sequence: gie
    "ጎ": ["ግ", "ጎ"],    // key sequence: go
    "ጠ": ["ጥ", "ጠ"],    // key sequence: Te
    "ጡ": ["ጥ", "ጡ"],   // key sequence: Tu
    "ጢ": ["ጥ", "ጢ"],    // key sequence: Ti
    "ጣ": ["ጥ", "ጣ"],    // key sequence: Ta
    "ጤ": ["ጥ", "ጢ", "ጤ"],    // key sequence: Tie
    "ጦ": ["ጥ", "ጦ"],    // key sequence: To
    "ጨ": ["ጭ", "ጨ"],    // key sequence: Ce
    "ጩ": ["ጭ", "ጩ"],   // key sequence: Cu
    "ጪ": ["ጭ", "ጪ"],    // key sequence: Ci
    "ጫ": ["ጭ", "ጫ"],    // key sequence: Ca
    "ጬ": ["ጭ", "ጪ", "ጬ"],    // key sequence: Cie
    "ጮ": ["ጭ", "ጮ"],    // key sequence: Co
    "ጰ": ["ጵ", "ጰ"],    // key sequence: Pe
    "ጱ": ["ጵ", "ጱ"],   // key sequence: Pu
    "ጲ": ["ጵ", "ጲ"],    // key sequence: Pi
    "ጳ": ["ጵ", "ጳ"],    // key sequence: Pa
    "ጴ": ["ጵ", "ጲ", "ጴ"],    // key sequence: Pie
    "ጶ": ["ጵ", "ጶ"],    // key sequence: Po
    "ጸ": ["ት", "ጽ", "ጸ"],    // key sequence: tse
    "ጹ": ["ት", "ጽ", "ጹ"],    // key sequence: tsu
    "ጺ": ["ት", "ጽ", "ጺ"],    // key sequence: tsi
    "ጻ": ["ት", "ጽ", "ጻ"],    // key sequence: tsa
    "ጼ": ["ት", "ጽ", "ጺ", "ጼ"],    // key sequence: tsie
    "ጽ": ["ት", "ጽ"],    // key sequence: ts
    "ጾ": ["ት", "ጽ", "ጾ"],    // key sequence: tso
    "ፀ": ["ት", "ጽ", "ፅ", "ፀ"],    // key sequence: tsse
    "ፁ": ["ት", "ጽ", "ፅ", "ፁ"],   // key sequence: tssu
    "ፂ": ["ት", "ጽ", "ፅ", "ፂ"],    // key sequence: tssi
    "ፃ": ["ት", "ጽ", "ፅ", "ፃ"],    // key sequence: tssa
    "ፄ": ["ት", "ጽ", "ፅ", "ፂ", "ፄ"],    // key sequence: tssie
    "ፅ": ["ት", "ጽ", "ፅ"],    // key sequence: tss
    "ፆ": ["ት", "ጽ", "ፅ", "ፆ"],    // key sequence: tsso
    "ፈ": ["ፍ", "ፈ"],    // key sequence: fe
    "ፉ": ["ፍ", "ፉ"],   // key sequence: fu
    "ፊ": ["ፍ", "ፊ"],    // key sequence: fi
    "ፋ": ["ፍ", "ፋ"],    // key sequence: fa
    "ፌ": ["ፍ", "ፊ", "ፌ"],    // key sequence: fie
    "ፎ": ["ፍ", "ፎ"],    // key sequence: fo
    "ፐ": ["ፕ", "ፐ"],    // key sequence: pe
    "ፑ": ["ፕ", "ፑ"],   // key sequence: pu
    "ፒ": ["ፕ", "ፒ"],    // key sequence: pi
    "ፓ": ["ፕ", "ፓ"],    // key sequence: pa
    "ፔ": ["ፕ", "ፒ", "ፔ"],    // key sequence: pie
    "ፖ": ["ፕ", "ፖ"],    // key sequence: po
    "ቨ": ["ቭ", "ቨ"],    // key sequence: ve
    "ቩ": ["ቭ", "ቩ"],   // key sequence: vu
    "ቪ": ["ቭ", "ቪ"],    // key sequence: vi
    "ቫ": ["ቭ", "ቫ"],    // key sequence: va
    "ቬ": ["ቭ", "ቪ", "ቬ"],    // key sequence: vie
    "ቮ": ["ቭ", "ቮ"],    // key sequence: vo
    "ሏ": ["ል", "ሉ", "ሏ"],    // key sequence: lua
    "ሗ": ["ህ", "ሕ", "ሑ", "ሗ"],   // key sequence: hhua
    "ሟ": ["ም", "ሙ", "ሟ"],    // key sequence: mua
    "ሧ": ["ስ", "ሥ", "ሡ", "ሧ"],    // key sequence: ssua
    "ሯ": ["ር", "ሩ", "ሯ"],    // key sequence: rua
    "ሷ": ["ስ", "ሱ", "ሷ"],    // key sequence: sua
    "ሿ": ["ሽ", "ሹ", "ሿ"],    // key sequence: xua
    "ቈ": ["ቅ", "ቁ", "ቈ"],   // key sequence: que
    "ቍ": ["ቅ", "ቁ", "ቍ"],    // key sequence: qu'
    "ቊ": ["ቅ", "ቁ", "ቊ"],    // key sequence: qui
    "ቋ": ["ቅ", "ቁ", "ቋ"],    // key sequence: qua
    "ቌ": ["ቅ", "ቁ", "ቊ", "ቌ"],    // key sequence: quie
    "ቧ": ["ብ", "ቡ", "ቧ"],    // key sequence: bua
    "ቷ": ["ት", "ቱ", "ቷ"],   // key sequence: tua
    "ቿ": ["ች", "ቹ", "ቿ"],    // key sequence: cua
    "ኈ": ["ህ", "ሕ", "ኅ", "ኁ", "ኈ"],    // key sequence: hhhue
    "ኍ": ["ህ", "ሕ", "ኅ", "ኁ", "ኍ"],    // key sequence: hhhu'
    "ኊ": ["ህ", "ሕ", "ኅ", "ኁ", "ኊ"],    // key sequence: hhhui
    "ኋ": ["ህ", "ሕ", "ኅ", "ኁ", "ኋ"],    // key sequence: hhhua
    "ኌ": ["ህ", "ሕ", "ኅ", "ኁ", "ኊ", "ኌ"],   // key sequence: hhhuie
    "ኗ": ["ን", "ኑ", "ኗ"],    // key sequence: nua
    "ኟ": ["ኝ", "ኙ", "ኟ"],    // key sequence: Nua
    "ኰ": ["ክ", "ኩ", "ኰ"],    // key sequence: kue
    "ኵ": ["ክ", "ኩ", "ኵ"],    // key sequence: ku'
    "ኲ": ["ክ", "ኩ", "ኲ"],    // key sequence: kui
    "ኳ": ["ክ", "ኩ", "ኳ"],   // key sequence: kua
    "ኴ": ["ክ", "ኩ", "ኲ", "ኴ"],    // key sequence: kuie
    "ዟ": ["ዝ", "ዙ", "ዟ"],    // key sequence: zua
    "ዧ": ["ዥ", "ዡ", "ዧ"],    // key sequence: Zua
    "ጇ": ["ጅ", "ጁ", "ጇ"],    // key sequence: jua
    "ጐ": ["ግ", "ጉ", "ጐ"],    // key sequence: gue
    "ጕ": ["ግ", "ጉ", "ጕ"],   // key sequence: gu'
    "ጒ": ["ግ", "ጉ", "ጒ"],    // key sequence: gui
    "ጓ": ["ግ", "ጉ", "ጓ"],    // key sequence: gua
    "ጔ": ["ግ", "ጉ", "ጒ", "ጔ"],    // key sequence: guie
    "ጧ": ["ጥ", "ጡ", "ጧ"],    // key sequence: Tua
    "ጯ": ["ጭ", "ጩ", "ጯ"],    // key sequence: Cua
    "ጷ": ["ጵ", "ጱ", "ጷ"],   // key sequence: Pua
    "ጿ": ["ት", "ጽ", "ጹ", "ጿ"],    // key sequence: tsua
    "ፏ": ["ፍ", "ፉ", "ፏ"],    // key sequence: fua
    "ፗ": ["ፕ", "ፑ", "ፗ"],    // key sequence: pua
    "ቯ": ["ቭ", "ቩ", "ቯ"],    // key sequence: vua
    };
    return sequences;
}

/**
 * Process keyupFirst.
 * @param {string} event Type of event.
 * @returns {bolean} The event.
 */
function keyupFirst(event) {
    return false;
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    this.accent = false;

    if (isLetter(ltr)) { // Set specified shift key for right or left.
        // phpcs:ignore
        // AZERTQSDFGWXCVB~!@#$%>
        if (ltr.match(/[~ዠዡዢዣዤዥዦዧጠጡጢጣጤጥጦጧጨጩጪጫጬጭጮጯ!@#$%]/)) {
            this.shiftright = true;
        // phpcs:ignore
        // YUIOPHJKLMN67890_+
        } else if (ltr.match(/[ዑጰጱጲጳጴጵጶጷኸኹኺኻኼኽኾኘኙኚኛኜኝኞኟዒዔዖ^&*()_+]/)) {
            this.shiftleft = true;
        }
    }

    // phpcs:ignore
    if (ltr.match(/[\\|@#€{}[\]~´`ñ]/i)) {
        this.alt = true;
    }
    // phpcs:ignore
    if (ltr.match(/[ëïöü]/i)) {
        this.shiftleft = true;
        this.caret = true;
    }
    if (ltr === 'ê') {
        this.caret = true;
    }
    if (ltr === 'ó' || ltr === 'á') {
        this.alt = true;
        this.accent = true;
    }
    if (ltr === 'ñ') {
        this.alt = true;
        this.tilde = true;
    }
    this.turnOn = function() {
        if (isLetter(this.chr)) {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        } else if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }

        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "next4";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyù').className = "next4";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "next4";
        }
    };
    this.turnOn = function(currSeqIndex) {
	console.log("Turning on: ", this.chr);
        var nextChar = this.chr;
        if (isCharSequence(nextChar)) {
            const seq = getSequence(nextChar);
            nextChar = seq[currSeqIndex];
	    console.log("Given index is: ", currSeqIndex);
	    console.log("New char is: ", nextChar);
        }
        if (isLetter(nextChar)) {
            document.getElementById(getKeyID(nextChar)).className = "next" + thenFinger(nextChar);
        } else if (nextChar === ' ') {
            document.getElementById(getKeyID(nextChar)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(nextChar)).className = "next" + thenFinger(nextChar);
        }

        if (nextChar === '\n' || nextChar === '\r\n' || nextChar === '\n\r' || nextChar === '\r') {
            document.getElementById('jkeyenter').className = "next4";
        }
        if (this.shiftleft && currSeqIndex === 0) {
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.shiftright && currSeqIndex === 0) {
            document.getElementById('jkeyshiftr').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyù').className = "next4";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "next4";
        }
    };
    this.turnOff = function() {
	var seq;
	if (isCharSequence(this.chr)) {
            seq = getSequence(this.chr);
	} else {
	    seq = [this.chr];
	}

	for (const char of seq) {
            if (isLetter(char)) {
                // phpcs:ignore
                const endsWithA = (char === "ሃ" || char === "ላ" ||
                    char === "ሓ" || char === "ማ" || char === "ሣ" || char === "ራ" ||
                    char === "ሳ" || char === "ሻ" || char === "ቃ" || char === "ባ" ||
                    char === "ቫ" || char === "ታ" || char === "ቻ" || char === "ኃ" ||
                    char === "ና" || char === "ኛ" || char === "ኣ" || char === "ካ" ||
                    char === "ኻ" || char === "ዋ" || char === "ዓ" || char === "ዛ" ||
                    char === "ዣ" || char === "ያ" || char === "ዳ" || char === "ጃ" ||
                    char === "ጋ" || char === "ጣ" || char === "ጫ" || char === "ጳ" ||
                    char === "ጻ" || char === "ፃ" || char === "ፋ" || char === "ፓ" ||
                    char === "ሏ" || char === "ሗ" || char === "ሟ" || char === "ሧ" ||
                    char === "ሯ" || char === "ሷ" || char === "ሿ" || char === "ቋ" ||
                    char === "ቧ" || char === "ቯ" || char === "ቷ" || char === "ቿ" ||
                    char === "ኋ" || char === "ኗ" || char === "ኟ" || char === "ኳ" ||
                    char === "ዓ" || char === "ዟ" || char === "ዧ" || char === "ኣ" ||
                    char === "ዐ" || char === "ጇ" || char === "ጓ" || char === "ጧ" ||
                    char === "ጯ" || char === "ጷ" || char === "ጿ" || char === "አ" ||
                    char === "ፏ" || char === "ፗ" || char === "አ" || char === "ኣ");

                if (char.match(/[አስድፍጅክል፤;ኽ]/i)) {
                    document.getElementById(getKeyID(char)).className = "finger" + thenFinger(char.toLowerCase());
                } else if (endsWithA) {
		    document.getElementById(getKeyID("አ")).className = "finger" + thenFinger("አ");
		} else if (char.match(/[ሥጽፅ]/i)) {
		    document.getElementById(getKeyID("ስ")).className = "finger" + thenFinger("ስ");
		} else {
                    document.getElementById(getKeyID(char)).className = "normal";
                }
            } else {
                document.getElementById(getKeyID(char)).className = "normal";
            }
	}

        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "normal";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "normal";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "normal";
        }
        if (this.accent) {
            document.getElementById('jkeyù').className = "normal";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "normal";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "normal";
        }
    };
}

/**
 * Set color flag based on current character.
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
        // phpcs:ignore
    //} else if (tCrka.match(/[²³&1|aáqw<>\\à0}pm)°^¨[ù%´=+~\-_$*\]µ£`]/i)) {
    } else if (tCrka.match(/[`~1!ቅአዝ0)ፕ፤፡/?\'"[{}\]|\\ ፟_ሃላሓማሣራሳሻቃባቫታቻኃናኛኣካኻዋዓዛዣያዳጃጋጣጫጳጻፃፋፓሏሗሟሧሯሷሿቋቧቯቷቿኋኗኟኳዟዧጇጓጧጯጷጿፏፗቍኍኵጕዥጵኣዐዓ]/i)) {
        return 2; // Highlight the correct key above in red.
        // phpcs:ignore
    //} else if (tCrka.match(/[é2@zsxç9{oóöl:/]/i)) { Ends with 
    } else if (tCrka.match(/[2@ውስሽ9\(ኦል።>ሥሆሎሖሞሦሮሶሾቆቦቮቶቾኆኖኞኦኮኾዎዖዞዦዮዶጆጎጦጮጶጾፆፎፖጽፅዖ]/i)) {
        return 1; // Highlight the correct key above in green.
        // phpcs:ignore
    //} else if (tCrka.match(/["3#eéë€êdc!8iíïk;.]/i)) {
    } else if (tCrka.match(/[.3#እድች8*ኢክ፣<ሀለሐመሠረሰሸቀበቨተቸኀነኘአከኸወኧዘዠየደጀገጠጨጰጸፀፈፐሂሊሒሚሢሪሲሺቂቢቪቲቺኂኒኚኽኪኺዊዒዚዢዪዲጂጊጢጪጲጺፂፊፒሄሌሔሜሤሬሴሼቄቤቬቴቼኄኔኜኤኬኼዌዔዜዤዬዴጄጌጤጬጴጼፄፌፔቈኈኰጐቌኌኴጔቊኊኲጒጭኤዒዔዕ]/i)) {
        return 4; // Highlight the correct key above in yellow.
        // phpcs:ignore
    //} else if (tCrka.match(/[\'4rf(5tgbv§6yhnñè7uúüj,?]/i)) {
    } else if (tCrka.match(/[4$ርፍቭ5%ትግብ6^ይህን7&ኡጅምሁሉሑሙሡሩሱሹቁቡቩቱቹኁኑኙኡኩኹዉዑዙዡዩዱጁጉጡጩጱጹፁፉፑሕኅኝጥዑ]/i)) {
        return 3; // Highlight the correct key above in blue.
    } else {
        return 6;
    }
}

/**
 * Get ID of key to highlight based on current character.
 * @param {string} tCrka The current character.
 * @returns {string}.
 */
function getKeyID(tCrka) {
    const endsWithE = (tCrka === "ሀ" || tCrka === "ለ" ||
	tCrka === "ሐ" || tCrka === "መ" || tCrka === "ሠ" || tCrka === "ረ" ||
	tCrka === "ሰ" || tCrka === "ሸ" || tCrka === "ቀ" || tCrka === "በ" ||
	tCrka === "ቨ" || tCrka === "ተ" || tCrka === "ቸ" || tCrka === "ኀ" ||
	tCrka === "ነ" || tCrka === "ኘ" || tCrka === "እ" || tCrka === "ከ" ||
	tCrka === "ኸ" || tCrka === "ወ" || tCrka === "ዘ" ||
	tCrka === "ዠ" || tCrka === "የ" || tCrka === "ደ" || tCrka === "ጀ" ||
	tCrka === "ገ" || tCrka === "ጠ" || tCrka === "ጨ" || tCrka === "ጰ" || 
	tCrka === "ጸ" || tCrka === "ፀ" || tCrka === "ፈ" || tCrka === "ፐ" ||
	tCrka === "ሄ" || tCrka === "ሌ" || tCrka === "ሔ" || tCrka === "ሜ" ||
	tCrka === "ሤ" || tCrka === "ሬ" || tCrka === "ሴ" || tCrka === "ሼ" ||
	tCrka === "ቄ" || tCrka === "ቤ" || tCrka === "ቬ" || tCrka === "ቴ" ||
	tCrka === "ቼ" || tCrka === "ኄ" || tCrka === "ኔ" || tCrka === "ኜ" ||
	tCrka === "ኤ" || tCrka === "ኬ" || tCrka === "ኼ" || tCrka === "ዌ" ||
	tCrka === "ዔ" || tCrka === "ዜ" || tCrka === "ዤ" || tCrka === "ዬ" ||
	tCrka === "ዴ" || tCrka === "ጄ" || tCrka === "ጌ" || tCrka === "ጤ" ||
	tCrka === "ጬ" || tCrka === "ጴ" || tCrka === "ጼ" || tCrka === "ፄ" ||
	tCrka === "ፌ" || tCrka === "ፔ" || tCrka === "ቈ" || tCrka === "ኈ" ||
	tCrka === "ኰ" || tCrka === "ጐ" || tCrka === "ቌ" || tCrka === "ኌ" ||
	tCrka === "ኴ" || tCrka === "ጔ" || tCrka === "ዕ" || tCrka === "ኧ");

    const endsWithA = (tCrka === "ሃ" || tCrka === "ላ" ||
	tCrka === "ሓ" || tCrka === "ማ" || tCrka === "ሣ" || tCrka === "ራ" ||
	tCrka === "ሳ" || tCrka === "ሻ" || tCrka === "ቃ" || tCrka === "ባ" ||
	tCrka === "ቫ" || tCrka === "ታ" || tCrka === "ቻ" || tCrka === "ኃ" ||
	tCrka === "ና" || tCrka === "ኛ" || tCrka === "ኣ" || tCrka === "ካ" ||
	tCrka === "ኻ" || tCrka === "ዋ" || tCrka === "ዓ" || tCrka === "ዛ" ||
	tCrka === "ዣ" || tCrka === "ያ" || tCrka === "ዳ" || tCrka === "ጃ" ||
	tCrka === "ጋ" || tCrka === "ጣ" || tCrka === "ጫ" || tCrka === "ጳ" ||
	tCrka === "ጻ" || tCrka === "ፃ" || tCrka === "ፋ" || tCrka === "ፓ" ||
	tCrka === "ሏ" || tCrka === "ሗ" || tCrka === "ሟ" || tCrka === "ሧ" ||
	tCrka === "ሯ" || tCrka === "ሷ" || tCrka === "ሿ" || tCrka === "ቋ" ||
	tCrka === "ቧ" || tCrka === "ቯ" || tCrka === "ቷ" || tCrka === "ቿ" ||
	tCrka === "ኋ" || tCrka === "ኗ" || tCrka === "ኟ" || tCrka === "ኳ" ||
	tCrka === "ዓ" || tCrka === "ዟ" || tCrka === "ዧ" || tCrka === "ኣ" ||
	tCrka === "ዐ" || tCrka === "ጇ" || tCrka === "ጓ" || tCrka === "ጧ" ||
	tCrka === "ጯ" || tCrka === "ጷ" || tCrka === "ጿ" || tCrka === "አ" ||
	tCrka === "ፏ" || tCrka === "ፗ");

    const endsWithU = (tCrka === "ኡ" || tCrka === "ዑ" ||
	tCrka === "ሁ" || tCrka === "ሉ" || tCrka === "ሑ" || tCrka === "ሙ" ||
	tCrka === "ሡ" || tCrka === "ሩ" || tCrka === "ሱ" || tCrka === "ሹ" ||
	tCrka === "ቁ" || tCrka === "ቡ" || tCrka === "ቩ" || tCrka === "ቱ" ||
	tCrka === "ቹ" || tCrka === "ኁ" || tCrka === "ኑ" || tCrka === "ኙ" ||
	tCrka === "ኡ" || tCrka === "ኩ" || tCrka === "ኹ" || tCrka === "ዉ" ||
	tCrka === "ዑ" || tCrka === "ዙ" || tCrka === "ዡ" || tCrka === "ዩ" ||
	tCrka === "ዱ" || tCrka === "ጁ" || tCrka === "ጉ" || tCrka === "ጡ" ||
	tCrka === "ጩ" || tCrka === "ጱ" || tCrka === "ጹ" || tCrka === "ፁ" ||
	tCrka === "ፉ" || tCrka === "ፑ");

    const endsWithI = (tCrka === "ሂ" || tCrka === "ሊ" ||
	tCrka === "ሒ" || tCrka === "ሚ" || tCrka === "ሢ" || tCrka === "ሪ" ||
	tCrka === "ሲ" || tCrka === "ሺ" || tCrka === "ቂ" || tCrka === "ቢ" ||
	tCrka === "ቪ" || tCrka === "ቲ" || tCrka === "ቺ" || tCrka === "ኂ" ||
	tCrka === "ኒ" || tCrka === "ኚ" || tCrka === "ኢ" || tCrka === "ኪ" ||
	tCrka === "ኺ" || tCrka === "ዊ" || tCrka === "ዒ" || tCrka === "ዚ" ||
	tCrka === "ዢ" || tCrka === "ዪ" || tCrka === "ዲ" || tCrka === "ጂ" ||
	tCrka === "ጊ" || tCrka === "ጢ" || tCrka === "ጪ" || tCrka === "ጲ" ||
	tCrka === "ጺ" || tCrka === "ፂ" || tCrka === "ፊ" || tCrka === "ፒ" ||
	tCrka === "ቊ" || tCrka === "ኊ" || tCrka === "ኲ" || tCrka === "ጒ");

    const endsWithO = (tCrka === "ሆ" || tCrka === "ሎ" || 
	tCrka === "ሖ" || tCrka === "ሞ" || tCrka === "ሦ" || tCrka === "ሮ" ||
	tCrka === "ሶ" || tCrka === "ሾ" || tCrka === "ቆ" || tCrka === "ቦ" ||
	tCrka === "ቮ" || tCrka === "ቶ" || tCrka === "ቾ" || tCrka === "ኆ" ||
	tCrka === "ኖ" || tCrka === "ኞ" || tCrka === "ኦ" || tCrka === "ኮ" ||
	tCrka === "ኾ" || tCrka === "ዎ" || tCrka === "ዖ" || tCrka === "ዞ" ||
	tCrka === "ዦ" || tCrka === "ዮ" || tCrka === "ዶ" || tCrka === "ጆ" ||
	tCrka === "ጎ" || tCrka === "ጦ" || tCrka === "ጮ" || tCrka === "ጶ" ||
	tCrka === "ጾ" || tCrka === "ፆ" || tCrka === "ፎ" || tCrka === "ፖ");

    if (tCrka === ' ') {
        return "jkeyspace";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '`' || tCrka === '~') {
        return "jkeycaret`" ;//`~
    } else if (tCrka === '1' || tCrka === '!') {
        return "jkey1" ;//!1
    } else if (tCrka === '2' || tCrka === '@') {
        return "jkey2" ;//@2
    } else if (tCrka === '3' || tCrka === '#') {
        return "jkey3" ;//#3
    } else if (tCrka === '4' || tCrka === '$') {
        return "jkey4" ;//$4
    } else if (tCrka === '5' || tCrka === '%') {
        return "jkey5" ;//%5
    } else if (tCrka === '6' || tCrka === '^') {
        return "jkey6" ;//^6
    } else if (tCrka === '7' || tCrka === '&') {
        return "jkey7" ;//&7
    } else if (tCrka === '8' || tCrka === '*') {
        return "jkey8" ;//*8
    } else if (tCrka === '9' || tCrka === '(') {
        return "jkey9" ;//(9
    } else if (tCrka === '0' || tCrka === ')') {
        return "jkey0" ;//)0
    } else if (tCrka === '፟' || tCrka === '_') {
        return "jkeyminus" ;//_-
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkey=" ;//+=
    } else if (tCrka === 'ቅ' || tCrka === 'q') {
        return "jkeyq" ;//Qቅ
    } else if (tCrka === 'ው' || tCrka === 'w') {
        return "jkeyw" ;//Wው
    } else if (tCrka === 'እ' || tCrka === 'ዕ' || tCrka === 'e' || endsWithE) {
        return "jkeye" ;//Eእ
    } else if (tCrka === 'ር' || tCrka === 'r') {
        return "jkeyr" ;//Rር
    } else if (tCrka === 'ት' || tCrka === 'ጥ' || tCrka === 't') {
        return "jkeyt" ;//Tት
    } else if (tCrka === 'ይ' || tCrka === 'y') {
        return "jkeyy" ;//Yይ
    } else if (tCrka === 'ኡ' || tCrka === 'ዑ' || endsWithU || tCrka === 'u') {
        return "jkeyu" ;//Uኡ
    } else if (tCrka === 'ኢ' || tCrka === 'ዒ' || endsWithI || tCrka === 'i') {
        return "jkeyi" ;//Iኢ
    } else if (tCrka === 'ኦ' || tCrka === 'ዖ' || endsWithO || tCrka === 'o') {
        return "jkeyo" ;//Oኦ
    } else if (tCrka === 'ፕ' || tCrka === 'ጵ' || tCrka === 'p') {
        return "jkeyp" ;//Pፕ
    } else if (tCrka === '[' || tCrka === '{') {
        return "jkey["; //{[
    } else if (tCrka === ']' || tCrka === '}') {
        return "jkey]";//}]
    } else if (tCrka === 'አ' || tCrka === 'ኣ' || endsWithA || tCrka === 'a') {
        return "jkeya";//Aአ
    } else if (tCrka === 'ስ' || tCrka === 'ጽ' || tCrka === 'ፅ' || tCrka === 'ሥ' || tCrka === 's') {
        return "jkeys";//Sስ
    } else if (tCrka === 'ድ' || tCrka === 'd') {
        return "jkeyd";//Dድ
    } else if (tCrka === 'ፍ' || tCrka === 'f') {
        return "jkeyf";//Fፍ
    } else if (tCrka === 'ግ' || tCrka === 'g') {
        return "jkeyg" ;//Gግ
    } else if (tCrka === 'ህ' || tCrka === 'ሕ' || tCrka === 'ኅ' || tCrka === 'h') {
        return "jkeyh" ;//Hህ
    } else if (tCrka === 'ጅ' || tCrka === 'j') {
        return "jkeyj";//Jጅ
    } else if (tCrka === 'ክ' || tCrka === 'ኽ' || tCrka === 'k') {
        return "jkeyk";//Kክ
    } else if (tCrka === 'ል' || tCrka === 'l') {
        return "jkeyl";//Lል
    } else if (tCrka === '፤' || tCrka === '፡' || tCrka === ';') {
        return "jkey፤";//፡፤
    } else if (tCrka === '\'' || tCrka === '\"' || tCrka === "ቍ" || tCrka === "ኍ" || tCrka === "ኵ" || tCrka === "ጕ") {
        return "jkey'" ;//"'
    } else if (tCrka === '\\' || tCrka === '|') {
        return "jkey\\" ;//|\
    } else if (tCrka === 'ዝ' || tCrka === 'ዥ' || tCrka === 'z') {
        return "jkeyz" ;//Zዝ
    } else if (tCrka === 'ሽ' || tCrka === 'x') {
        return "jkeyx" ;//Xሽ
    } else if (tCrka === 'ች' || tCrka === 'ጭ' || tCrka === 'c') {
        return "jkeyc" ;//Cች
    } else if (tCrka === 'ቭ' || tCrka === 'v') {
        return "jkeyv" ;//Vቭ
    } else if (tCrka === 'ብ' || tCrka === 'b') {
        return "jkeyb" ;//Bብ
    } else if (tCrka === 'ኝ' || tCrka === 'ን' || tCrka === 'n') {
        return "jkeyn" ;//Nን
    } else if (tCrka === 'ም' || tCrka === 'm') {
        return "jkeym" ;//Mም
    } else if (tCrka === '፣' || tCrka === '<' || tCrka === ',') {
        return "jkeycomma" ;//፣
    } else if (tCrka === '።' || tCrka === '>' || tCrka === '.') {
        return "jkeyperiod" ;//።
    } else if (tCrka === '/' || tCrka === '?') {
        return "jkey/" ;//?/
    } else {
        return "jkey" + tCrka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 * @param {string} str The current letter.
 * @returns {(number|Array)}.
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[!-ﻼ]/);
}

