import domReady from '@wordpress/dom-ready';
import {registerPlugin} from "@wordpress/plugins";
import {PluginDocumentSettingPanel} from '@wordpress/edit-post';
import { ToggleControl } from '@wordpress/components';
import {useCachingActive} from "./hooks";

domReady(()=>{

    registerPlugin("cached-community", {
        render: () => {

            const {i18n} = GutenbergCachedCommunity;
            const [active, setActive] = useCachingActive();


            return <PluginDocumentSettingPanel
                title="Cached Community"
            >
                <ToggleControl
                    label={active ? i18n.caching_activated_label : i18n.caching_deactivated_label}
                    help={i18n.caching_help}
                    checked={active}
                    onChange={setActive}
                />
            </PluginDocumentSettingPanel>
        }
    });

});