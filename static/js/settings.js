/**
 * Payunity
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2016 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

pimcore.registerNS("pimcore.plugin.payunity.settings");
pimcore.plugin.payunity.settings = Class.create({

    initialize: function () {
        this.getData();
    },

    getData: function () {
        Ext.Ajax.request({
            url: "/plugin/Payunity/admin/get",
            success: function (response)
            {
                this.data = Ext.decode(response.responseText);

                this.getTabPanel();

            }.bind(this)
        });
    },

    getValue: function (key) {
        var current = null;

        if(this.data.values.hasOwnProperty(key)) {
            current = this.data.values[key];
        }

        if (typeof current != "object" && typeof current != "array" && typeof current != "function") {
            return current;
        }

        return "";
    },

    getTabPanel: function () {

        if (!this.panel) {
            this.panel = Ext.create('Ext.panel.Panel', {
                id: "coreshop_payunity",
                title: t("coreshop_payunity"),
                iconCls: "coreshop_icon_payunity",
                border: false,
                layout: "fit",
                closable:true
            });

            var tabPanel = Ext.getCmp("pimcore_panel_tabs");
            tabPanel.add(this.panel);
            tabPanel.setActiveItem("coreshop_payunity");


            this.panel.on("destroy", function () {
                pimcore.globalmanager.remove("coreshop_payunity");
            }.bind(this));


            this.layout = Ext.create('Ext.form.Panel', {
                bodyStyle:'padding:20px 5px 20px 5px;',
                border: false,
                autoScroll: true,
                forceLayout: true,
                defaultType: 'textfield',
                defaults: {
                    forceLayout: true
                },
                fieldDefaults: {
                    labelWidth: 250
                },
                buttons: [
                    {
                        text: "Save",
                        handler: this.save.bind(this),
                        iconCls: "pimcore_icon_apply"
                    }
                ],
                items: [
                    {
                        fieldLabel: t('coreshop_payunity_channelId'),
                        name: 'PAYUNITY.CHANNELID',
                        value: this.getValue("PAYUNITY.CHANNELID"),
                        enableKeyEvents: true
                    },
                    {
                        fieldLabel: t('coreshop_payunity_mode'),
                        name: 'PAYUNITY.MODE',
                        value: this.getValue("PAYUNITY.MODE"),
                        enableKeyEvents: true
                    },
                    {
                        xtype: "checkbox",
                        fieldLabel: t('coreshop_payunity_sandbox'),
                        name: 'PAYUNITY.SANDBOX',
                        value: this.getValue("PAYUNITY.SANDBOX")
                    },
                    {
                        fieldLabel: t('coreshop_payunity_senderId'),
                        name: 'PAYUNITY.SENDERID',
                        value: this.getValue("PAYUNITY.SENDERID"),
                        enableKeyEvents: true
                    },
                    {
                        fieldLabel: t('coreshop_payunity_userId'),
                        name: 'PAYUNITY.USERID',
                        value: this.getValue("PAYUNITY.USERID"),
                        enableKeyEvents: true
                    },
                    {
                        fieldLabel: t('coreshop_payunity_userPwd'),
                        name: 'PAYUNITY.USERPWD',
                        value: this.getValue("PAYUNITY.USERPWD"),
                        enableKeyEvents: true
                    }
                ]
            });

            this.panel.add(this.layout);

            pimcore.layout.refresh();
        }

        return this.panel;
    },

    activate: function () {
        var tabPanel = Ext.getCmp("pimcore_panel_tabs");
        tabPanel.activate("coreshop_payunity");
    },

    save: function () {
        var values = this.layout.getForm().getFieldValues();

        Ext.Ajax.request({
            url: "/plugin/Payunity/admin/set",
            method: "post",
            params: {
                data: Ext.encode(values)
            },
            success: function (response) {
                try {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        pimcore.helpers.showNotification(t("success"), t("coreshop_payunity_save_success"), "success");
                    } else {
                        pimcore.helpers.showNotification(t("error"), t("coreshop_payunity_save_error"),
                            "error", t(res.message));
                    }
                } catch(e) {
                    pimcore.helpers.showNotification(t("error"), t("coreshop_payunity_save_error"), "error");
                }
            }
        });
    }
});