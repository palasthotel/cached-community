import {useSelect, useDispatch } from '@wordpress/data';

export const useCachingActive = ()=>{
    const deactivated = useSelect(select=>select("core/editor").getEditedPostAttribute("meta")?.cached_community_deactivate_caching);
    const dispatch = useDispatch('core/editor', [deactivated]);
    return [
        !deactivated,
        (_active)=>{
            dispatch.editPost({
                meta: {
                    cached_community_deactivate_caching: !_active,
                }
            })
        }
    ]

}