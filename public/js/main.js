Vue.use(VeeValidate);

var app = new Vue({
    el: '#main',
    data: function() {
        return {
            link: "",
            short: ""
        }
    },
    methods: {
        onClickCopy: function() {
            const el = document.createElement('textarea');
            el.value = this.short;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        },
        onClickShorten: function() {
            var data = {
                link: this.link
            };
            
            let $this = this

            this.$validator.validate().then(result => {
                if (result) {
                    this.createLink(data).then(function(res) {
                        $this.short = "http://" + location.host + "/" + res.data
                        setTimeout(function() {
                            var copy = $this.$refs.copy
                            copy.select()
                            document.execCommand("copy")
                        }, 0);                
                    });
                }
            });            
        },
        createLink: function(data) {            
            return fetch("/api/v1/create", {
                method: "POST",
                headers: {
                    "Content-type": "application/json"
                },
                body: JSON.stringify(data)
            }).then(function(res) {
                return res.json();
            });
        }
    }
})