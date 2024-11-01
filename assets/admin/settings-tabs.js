import './style/settings-tabs.scss';

class wadiSettingsTabs {
    init() {

        this.Tabs()

    }

    Tabs() {
        const tab = document.querySelector('.tabs');
        const tabButtons = tab.querySelectorAll('[role="tab"]');
        const tabPanels = Array.from(tab.querySelectorAll('[role="tabpanel"]'));
        
        function tabClickHandler(e) {
            // e.preventDefault();

            //Hide All Tabpane
            tabPanels.forEach(panel => {
                panel.hidden = 'true';
            });
            
            //Deselect Tab Button
            tabButtons.forEach( button => {
                button.setAttribute('aria-selected', 'false');
            });
            
            //Mark New Tab
            e.currentTarget.setAttribute('aria-selected', 'true');
            
            //Show New Tab
            const { id } = e.currentTarget;
            
            const currentTab = tabPanels.find(
                (panel) => panel.getAttribute('aria-labelledby') === id
            );

            currentTab.hidden = false;
  
        }
        
        tabButtons.forEach(button => {
            button.addEventListener('click', tabClickHandler);
        })

        function openByHash() {
            const tab = document.querySelector('.tabs');
            const tabButtons = tab.querySelectorAll('[role="tab"]');

            const hash = window.location.hash;

            if(hash) {
                let splittedHash = hash.split('=');
                if(splittedHash) {
                    document.querySelector(`#${splittedHash[1]}`).click();
                }
            } else {
                 let tabHref = `tab=${tabButtons[0].href}`
                 if(tabHref) {
                     let splittedHref= tabHref.split('#');
    
                     window.location.hash = `#${splittedHref[1]}`;
                 }
            }
        }
        openByHash();
      }

}
    
const wadiTabs = new wadiSettingsTabs;

wadiTabs.init();

