import account from "../../../../fixtures/account";
import {OmakazitAdminAccount} from "../../../../page-objects/admin/omakazit/account";

describe('Disable customer registration tests', () => {
    it('Enable customer registration', () => {
        OmakazitAdminAccount.login()
        cy.get('#menu-magento-backend-stores').click()
        cy.get('.item-system-config').click()
        cy.wait(4000)
        cy.get('.tab-omakazit').click()
        cy.get('.tab-omakazit ul li').contains('Disable Customer').click()
        cy.get('#disable_customer_customer_account_registration_enabled_inherit').check({force: true})
        cy.wait(4000)
        cy.get('#save').click()
        cy.wait(4000)
        cy.contains('You saved the configuration.').should('exist')
    })

    it('Customer registration is enabled', () => {
        cy.visit(account.routes.accountCreate);
        cy.contains('Create New Customer Account').should('exist')
        cy.get('.panel.header').contains('Create an Account').should('exist');
    })

    it('Disable customer registration', () => {
        OmakazitAdminAccount.login()
        cy.get('#menu-magento-backend-stores').click()
        cy.get('.item-system-config').click()
        cy.wait(4000)
        cy.get('.tab-omakazit').click()
        cy.get('.tab-omakazit ul li').contains('Disable Customer').click()
        cy.wait(4000)
        cy.get('#disable_customer_customer_account_registration_enabled_inherit').uncheck({force: true})
        cy.get('#disable_customer_customer_account_registration_enabled').select('No')
        cy.get('#save').click()
        cy.wait(4000)
        cy.contains('You saved the configuration.').should('exist')
    })

    it('Customer registration is disabled', () => {
        cy.visit(account.routes.accountCreate);
        cy.contains('If you have an account, sign in with your email address.').should('exist');
        cy.get('.panel.header').contains('Create an Account').should('not.exist');
    })
})
