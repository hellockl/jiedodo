var repayment = new Vue({
    el: "#main",
    data() {
        return {
            personInfo: personInfo,
            lend: lend,
            repay: repay,
            others: others
        }
    },
    filters: {
        myFilter(val) {
            if (!val) return ''
            return val.toFixed(2)
        }
    }
})