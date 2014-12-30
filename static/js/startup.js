pimcore.registerNS("pimcore.plugin.Payunity");

pimcore.plugin.Payunity = Class.create(pimcore.plugin.admin, {
    getClassName: function() {
        return "pimcore.plugin.Payunity";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },
 
    pimcoreReady: function (params,broker){
        // alert("Example Ready!");
    }
});

var PayunityPlugin = new pimcore.plugin.Payunity();

