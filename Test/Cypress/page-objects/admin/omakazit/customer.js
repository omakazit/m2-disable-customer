import account from "../../../fixtures/account.json";

export class OmakazitAdminCustomer {
    static disableCustomer() {
        this.#updateStatusAccount(1);
    }

    static enableCustomer() {
        this.#updateStatusAccount(0);
    }

    static #updateStatusAccount(status) {
        cy.get('#menu-magento-customer-customer').click()
        cy.get('.item-customer-manage').click()
        cy.wait(4000)
        cy.get('button[data-action="grid-filter-expand"]').eq(0).click()
        cy.get('input[name="email"]').eq(0).clear().type(account.customer.customer.email)
        cy.get('button[data-action="grid-filter-apply"]').eq(0).click()
        cy.get('.data-grid-cell-content').contains(new RegExp("^" + account.customer.customer.email + "$"))
            .parents('tr')
            .find('.action-menu-item')
            .click()
        cy.get('#tab_customer').click()
        cy.get('input[name="customer[email]"]').should('have.value', account.customer.customer.email)

        if (status === 0) {
            cy.get('input[name="customer[is_disabled]"]').uncheck({force: true})
            const isDisabled = 'No';
        } else {
            cy.get('input[name="customer[is_disabled]"]').check({force: true})
            const isDisabled = 'Yes';
        }

        cy.get('#save_and_continue').click()
        cy.wait(4000)
        cy.contains('You saved the customer.').should('exist')
        cy.contains('Account Disabled:').parents('tr').find('td').should('contain.text', isDisabled)
        cy.get('#tab_customer').click()
        cy.get('input[name="customer[is_disabled]"]').should('have.value', status)
    }
}
