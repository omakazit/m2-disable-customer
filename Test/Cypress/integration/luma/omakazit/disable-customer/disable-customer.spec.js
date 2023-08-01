import account from "../../../../fixtures/account";
import {Account} from "../../../../page-objects/luma/account";
import {OmakazitAdminAccount} from "../../../../page-objects/admin/omakazit/account";
import {OmakazitAdminCustomer} from "../../../../page-objects/admin/omakazit/customer";

describe('Admin module tests', () => {
    it('Enable module', () => {
        OmakazitAdminAccount.login()
        cy.get('#menu-magento-backend-stores').click()
        cy.get('.item-system-config').click()
        cy.wait(4000)
        cy.get('.tab-omakazit').click()
        cy.get('.tab-omakazit ul li').contains('Disable Customer').click()
        cy.get('#disable_customer_general_enabled_inherit').check({force: true})
        cy.wait(4000)
        cy.get('#save').click()
        cy.wait(4000)
        cy.contains('You saved the configuration.').should('exist')
    })

    it('Can filter by disabled account status', () => {
        OmakazitAdminAccount.login()
        cy.get('#menu-magento-customer-customer').click()
        cy.get('.item-customer-manage').click()
        cy.wait(4000)
        cy.get('button[data-action="grid-filter-expand"]').eq(0).click()
        cy.get('select[name="is_disabled"]').should('have.length', 3)
    })
})

describe('Login tests', () => {
    it('Enable customer account', () => {
        OmakazitAdminAccount.login()
        OmakazitAdminCustomer.enableCustomer()
    })

    it('Login with an enabled account', () => {
        Account.login(account.customer.customer.email, account.customer.password)
        Account.isLoggedIn()
    })

    it('Disable customer account', () => {
        OmakazitAdminAccount.login()
        OmakazitAdminCustomer.disableCustomer()
    })

    it('Login with a disabled account', () => {
        Account.login(account.customer.customer.email, account.customer.password)
        cy.contains('Your account is disabled. Please contact customer service.').should('exist')
    })
})
