<?php

namespace Websmurf\LaravelExactOnline;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Picqer\Financials\Exact\Connection;
use RuntimeException;
use stdClass;
use function json_decode;
use function json_encode;

/**
 * @package Websmurf\LaravelExactOnline
 *
 * @method \Picqer\Financials\Exact\AbsenceRegistration AbsenceRegistration(array $attributes = [])
 * @method \Picqer\Financials\Exact\AbsenceRegistrationTransaction AbsenceRegistrationTransaction(array $attributes = [])
 * @method \Picqer\Financials\Exact\AcceptQuotation AcceptQuotation(array $attributes = [])
 * @method \Picqer\Financials\Exact\Account Account(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountClass AccountClass(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountClassification AccountClassification(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountClassificationName AccountClassificationName(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountDocument AccountDocument(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountDocumentCount AccountDocumentCount(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountDocumentFolder AccountDocumentFolder(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountInvolvedAccount AccountInvolvedAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountItem AccountItem(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountOwner AccountOwner(array $attributes = [])
 * @method \Picqer\Financials\Exact\AccountantInfo AccountantInfo(array $attributes = [])
 * @method \Picqer\Financials\Exact\ActiveEmployment ActiveEmployment(array $attributes = [])
 * @method \Picqer\Financials\Exact\Address Address(array $attributes = [])
 * @method \Picqer\Financials\Exact\AddressState AddressState(array $attributes = [])
 * @method \Picqer\Financials\Exact\AgingOverview AgingOverview(array $attributes = [])
 * @method \Picqer\Financials\Exact\AgingOverviewByAccount AgingOverviewByAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\AgingPayablesList AgingPayablesList(array $attributes = [])
 * @method \Picqer\Financials\Exact\AgingPayablesListByAgeGroup AgingPayablesListByAgeGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\AgingReceivablesList AgingReceivablesList(array $attributes = [])
 * @method \Picqer\Financials\Exact\AgingReceivablesListByAgeGroup AgingReceivablesListByAgeGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\AssemblyOrder AssemblyOrder(array $attributes = [])
 * @method \Picqer\Financials\Exact\Asset Asset(array $attributes = [])
 * @method \Picqer\Financials\Exact\AssetGroup AssetGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\AvailableFeature AvailableFeature(array $attributes = [])
 * @method \Picqer\Financials\Exact\Bank Bank(array $attributes = [])
 * @method \Picqer\Financials\Exact\BankAccount BankAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\BankEntry BankEntry(array $attributes = [])
 * @method \Picqer\Financials\Exact\BankEntryLine BankEntryLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\BatchNumber BatchNumber(array $attributes = [])
 * @method \Picqer\Financials\Exact\BillOfMaterialMaterial BillOfMaterialMaterial(array $attributes = [])
 * @method \Picqer\Financials\Exact\BillOfMaterialVersion BillOfMaterialVersion(array $attributes = [])
 * @method \Picqer\Financials\Exact\Budget Budget(array $attributes = [])
 * @method \Picqer\Financials\Exact\BudgetScenario BudgetScenario(array $attributes = [])
 * @method \Picqer\Financials\Exact\BulkGLClassification BulkGLClassification(array $attributes = [])
 * @method \Picqer\Financials\Exact\ByProductReceipt ByProductReceipt(array $attributes = [])
 * @method \Picqer\Financials\Exact\ByProductReversal ByProductReversal(array $attributes = [])
 * @method \Picqer\Financials\Exact\CashEntry CashEntry(array $attributes = [])
 * @method \Picqer\Financials\Exact\CashEntryLine CashEntryLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\CommunicationNote CommunicationNote(array $attributes = [])
 * @method \Picqer\Financials\Exact\Complaint Complaint(array $attributes = [])
 * @method \Picqer\Financials\Exact\Contact Contact(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostEntryExpensesByProject CostEntryExpensesByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostEntryRecentAccount CostEntryRecentAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostEntryRecentAccountsByProject CostEntryRecentAccountsByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostEntryRecentCostType CostEntryRecentCostType(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostEntryRecentCostTypesByProject CostEntryRecentCostTypesByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostEntryRecentExpensesByProject CostEntryRecentExpensesByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostEntryRecentProject CostEntryRecentProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostTransaction CostTransaction(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostType CostType(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostTypesByDate CostTypesByDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostTypesByProjectAndDate CostTypesByProjectAndDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\Costcenter Costcenter(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostsByDate CostsByDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\CostsById CostsById(array $attributes = [])
 * @method \Picqer\Financials\Exact\Costunit Costunit(array $attributes = [])
 * @method \Picqer\Financials\Exact\Currency Currency(array $attributes = [])
 * @method \Picqer\Financials\Exact\CurrentYearAfterEntry CurrentYearAfterEntry(array $attributes = [])
 * @method \Picqer\Financials\Exact\CurrentYearProcessed CurrentYearProcessed(array $attributes = [])
 * @method \Picqer\Financials\Exact\DefaultAddressForAccount DefaultAddressForAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\DefaultMailbox DefaultMailbox(array $attributes = [])
 * @method \Picqer\Financials\Exact\Department Department(array $attributes = [])
 * @method \Picqer\Financials\Exact\DepreciationMethod DepreciationMethod(array $attributes = [])
 * @method \Picqer\Financials\Exact\DirectDebitMandate DirectDebitMandate(array $attributes = [])
 * @method \Picqer\Financials\Exact\Division Division(array $attributes = [])
 * @method \Picqer\Financials\Exact\DivisionClass DivisionClass(array $attributes = [])
 * @method \Picqer\Financials\Exact\DivisionClassName DivisionClassName(array $attributes = [])
 * @method \Picqer\Financials\Exact\DivisionClassValue DivisionClassValue(array $attributes = [])
 * @method \Picqer\Financials\Exact\Document Document(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentAttachment DocumentAttachment(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentCategorie DocumentCategorie(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentCategory DocumentCategory(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentFolder DocumentFolder(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentType DocumentType(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentTypeCategory DocumentTypeCategory(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentTypeFolder DocumentTypeFolder(array $attributes = [])
 * @method \Picqer\Financials\Exact\DocumentsAttachment DocumentsAttachment(array $attributes = [])
 * @method \Picqer\Financials\Exact\Employee Employee(array $attributes = [])
 * @method \Picqer\Financials\Exact\Employment Employment(array $attributes = [])
 * @method \Picqer\Financials\Exact\EmploymentContract EmploymentContract(array $attributes = [])
 * @method \Picqer\Financials\Exact\EmploymentContractFlexPhase EmploymentContractFlexPhase(array $attributes = [])
 * @method \Picqer\Financials\Exact\EmploymentEndReason EmploymentEndReason(array $attributes = [])
 * @method \Picqer\Financials\Exact\EmploymentInternalRate EmploymentInternalRate(array $attributes = [])
 * @method \Picqer\Financials\Exact\EmploymentOrganization EmploymentOrganization(array $attributes = [])
 * @method \Picqer\Financials\Exact\EmploymentSalary EmploymentSalary(array $attributes = [])
 * @method \Picqer\Financials\Exact\ExchangeRate ExchangeRate(array $attributes = [])
 * @method \Picqer\Financials\Exact\FinancialPeriod FinancialPeriod(array $attributes = [])
 * @method \Picqer\Financials\Exact\GLAccount GLAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\GLAccountClassificationMapping GLAccountClassificationMapping(array $attributes = [])
 * @method \Picqer\Financials\Exact\GLClassification GLClassification(array $attributes = [])
 * @method \Picqer\Financials\Exact\GLScheme GLScheme(array $attributes = [])
 * @method \Picqer\Financials\Exact\GLTransactionSource GLTransactionSource(array $attributes = [])
 * @method \Picqer\Financials\Exact\GLTransactionType GLTransactionType(array $attributes = [])
 * @method \Picqer\Financials\Exact\GeneralJournalEntry GeneralJournalEntry(array $attributes = [])
 * @method \Picqer\Financials\Exact\GeneralJournalEntryLine GeneralJournalEntryLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\GetMostRecentlyUsedDivision GetMostRecentlyUsedDivision(array $attributes = [])
 * @method \Picqer\Financials\Exact\GoodsDelivery GoodsDelivery(array $attributes = [])
 * @method \Picqer\Financials\Exact\GoodsDeliveryLine GoodsDeliveryLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\GoodsReceipt GoodsReceipt(array $attributes = [])
 * @method \Picqer\Financials\Exact\GoodsReceiptLine GoodsReceiptLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\HostingOpportunity HostingOpportunity(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourCostType HourCostType(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourEntryActivitiesByProject HourEntryActivitiesByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourEntryRecentAccount HourEntryRecentAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourEntryRecentAccountsByProject HourEntryRecentAccountsByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourEntryRecentActivitiesByProject HourEntryRecentActivitiesByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourEntryRecentHourType HourEntryRecentHourType(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourEntryRecentHourTypesByProject HourEntryRecentHourTypesByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourEntryRecentProject HourEntryRecentProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourType HourType(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourTypesByDate HourTypesByDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\HourTypesByProjectAndDate HourTypesByProjectAndDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\HoursByDate HoursByDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\HoursById HoursById(array $attributes = [])
 * @method \Picqer\Financials\Exact\ImportNotification ImportNotification(array $attributes = [])
 * @method \Picqer\Financials\Exact\ImportNotificationDetail ImportNotificationDetail(array $attributes = [])
 * @method \Picqer\Financials\Exact\InvoiceSalesOrder InvoiceSalesOrder(array $attributes = [])
 * @method \Picqer\Financials\Exact\InvoiceTerm InvoiceTerm(array $attributes = [])
 * @method \Picqer\Financials\Exact\InvolvedUser InvolvedUser(array $attributes = [])
 * @method \Picqer\Financials\Exact\InvolvedUserRole InvolvedUserRole(array $attributes = [])
 * @method \Picqer\Financials\Exact\Item Item(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemAssortment ItemAssortment(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemAssortmentProperty ItemAssortmentProperty(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemDetailsByID ItemDetailsByID(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemExtraField ItemExtraField(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemGroup ItemGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemVersion ItemVersion(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemWarehouse ItemWarehouse(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemWarehousePlanningDetail ItemWarehousePlanningDetail(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemWarehousePlanningDetails ItemWarehousePlanningDetails(array $attributes = [])
 * @method \Picqer\Financials\Exact\ItemWarehouseStorageLocation ItemWarehouseStorageLocation(array $attributes = [])
 * @method \Picqer\Financials\Exact\JobGroup JobGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\JobTitle JobTitle(array $attributes = [])
 * @method \Picqer\Financials\Exact\Journal Journal(array $attributes = [])
 * @method \Picqer\Financials\Exact\JournalStatusByFinancialPeriod JournalStatusByFinancialPeriod(array $attributes = [])
 * @method \Picqer\Financials\Exact\JournalStatusList JournalStatusList(array $attributes = [])
 * @method \Picqer\Financials\Exact\Layout Layout(array $attributes = [])
 * @method \Picqer\Financials\Exact\LeaveBuildUpRegistration LeaveBuildUpRegistration(array $attributes = [])
 * @method \Picqer\Financials\Exact\LeaveRegistration LeaveRegistration(array $attributes = [])
 * @method \Picqer\Financials\Exact\MailMessage MailMessage(array $attributes = [])
 * @method \Picqer\Financials\Exact\MailMessageAttachment MailMessageAttachment(array $attributes = [])
 * @method \Picqer\Financials\Exact\MailMessagesSent MailMessagesSent(array $attributes = [])
 * @method \Picqer\Financials\Exact\Mailbox Mailbox(array $attributes = [])
 * @method \Picqer\Financials\Exact\ManufacturingSetting ManufacturingSetting(array $attributes = [])
 * @method \Picqer\Financials\Exact\MaterialIssue MaterialIssue(array $attributes = [])
 * @method \Picqer\Financials\Exact\MaterialReversal MaterialReversal(array $attributes = [])
 * @method \Picqer\Financials\Exact\Me Me(array $attributes = [])
 * @method \Picqer\Financials\Exact\OfficialReturn OfficialReturn(array $attributes = [])
 * @method \Picqer\Financials\Exact\Operation Operation(array $attributes = [])
 * @method \Picqer\Financials\Exact\OperationResource OperationResource(array $attributes = [])
 * @method \Picqer\Financials\Exact\Opportunity Opportunity(array $attributes = [])
 * @method \Picqer\Financials\Exact\OpportunityContact OpportunityContact(array $attributes = [])
 * @method \Picqer\Financials\Exact\OpportunityDocuments OpportunityDocuments(array $attributes = [])
 * @method \Picqer\Financials\Exact\OpportunityDocumentsCount OpportunityDocumentsCount(array $attributes = [])
 * @method \Picqer\Financials\Exact\OutstandingInvoicesOverview OutstandingInvoicesOverview(array $attributes = [])
 * @method \Picqer\Financials\Exact\PayablesList PayablesList(array $attributes = [])
 * @method \Picqer\Financials\Exact\PayablesListByAccount PayablesListByAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\PayablesListByAccountAndAgeGroup PayablesListByAccountAndAgeGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\PayablesListByAgeGroup PayablesListByAgeGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\Payment Payment(array $attributes = [])
 * @method \Picqer\Financials\Exact\PaymentCondition PaymentCondition(array $attributes = [])
 * @method \Picqer\Financials\Exact\PlannedSalesReturnLine PlannedSalesReturnLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\PreferredMailbox PreferredMailbox(array $attributes = [])
 * @method \Picqer\Financials\Exact\PreferredMailboxForOperation PreferredMailboxForOperation(array $attributes = [])
 * @method \Picqer\Financials\Exact\PreviousYearAfterEntry PreviousYearAfterEntry(array $attributes = [])
 * @method \Picqer\Financials\Exact\PreviousYearProcessed PreviousYearProcessed(array $attributes = [])
 * @method \Picqer\Financials\Exact\PriceList PriceList(array $attributes = [])
 * @method \Picqer\Financials\Exact\PrintQuotation PrintQuotation(array $attributes = [])
 * @method \Picqer\Financials\Exact\PrintedSalesInvoice PrintedSalesInvoice(array $attributes = [])
 * @method \Picqer\Financials\Exact\PrintedSalesOrder PrintedSalesOrder(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProcessPayment ProcessPayment(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProcessStockCount ProcessStockCount(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProcessWarehouseTransfer ProcessWarehouseTransfer(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProductionArea ProductionArea(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProfitLossOverview ProfitLossOverview(array $attributes = [])
 * @method \Picqer\Financials\Exact\Project Project(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectBudgetType ProjectBudgetType(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectHourBudget ProjectHourBudget(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectPlanning ProjectPlanning(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectPlanningRecurring ProjectPlanningRecurring(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectRestrictionEmployee ProjectRestrictionEmployee(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectRestrictionItem ProjectRestrictionItem(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectRestrictionRebilling ProjectRestrictionRebilling(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectWBSByProject ProjectWBSByProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\ProjectWBSByProjectAndWBS ProjectWBSByProjectAndWBS(array $attributes = [])
 * @method \Picqer\Financials\Exact\PurchaseEntry PurchaseEntry(array $attributes = [])
 * @method \Picqer\Financials\Exact\PurchaseEntryLine PurchaseEntryLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\PurchaseInvoice PurchaseInvoice(array $attributes = [])
 * @method \Picqer\Financials\Exact\PurchaseInvoiceLine PurchaseInvoiceLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\PurchaseOrder PurchaseOrder(array $attributes = [])
 * @method \Picqer\Financials\Exact\PurchaseOrderLine PurchaseOrderLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\Quotation Quotation(array $attributes = [])
 * @method \Picqer\Financials\Exact\QuotationLine QuotationLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReasonCode ReasonCode(array $attributes = [])
 * @method \Picqer\Financials\Exact\Receivable Receivable(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReceivablesList ReceivablesList(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReceivablesListByAccount ReceivablesListByAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReceivablesListByAccountAndAgeGroup ReceivablesListByAccountAndAgeGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReceivablesListByAgeGroup ReceivablesListByAgeGroup(array $attributes = [])
 * @method \Picqer\Financials\Exact\RecentCost RecentCost(array $attributes = [])
 * @method \Picqer\Financials\Exact\RecentCostsByNumberOfWeeks RecentCostsByNumberOfWeeks(array $attributes = [])
 * @method \Picqer\Financials\Exact\RecentHour RecentHour(array $attributes = [])
 * @method \Picqer\Financials\Exact\RecentTimeTransaction RecentTimeTransaction(array $attributes = [])
 * @method \Picqer\Financials\Exact\RejectQuotation RejectQuotation(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReopenQuotation ReopenQuotation(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReportingBalance ReportingBalance(array $attributes = [])
 * @method \Picqer\Financials\Exact\Returns Returns(array $attributes = [])
 * @method \Picqer\Financials\Exact\RevenueList RevenueList(array $attributes = [])
 * @method \Picqer\Financials\Exact\RevenueListByYear RevenueListByYear(array $attributes = [])
 * @method \Picqer\Financials\Exact\RevenueListByYearAndStatus RevenueListByYearAndStatus(array $attributes = [])
 * @method \Picqer\Financials\Exact\ReviewQuotation ReviewQuotation(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesEntry SalesEntry(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesEntryLine SalesEntryLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesInvoice SalesInvoice(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesInvoiceLine SalesInvoiceLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesItemPrice SalesItemPrice(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesOrder SalesOrder(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesOrderID SalesOrderID(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesOrderLine SalesOrderLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesPriceListDetail SalesPriceListDetail(array $attributes = [])
 * @method \Picqer\Financials\Exact\SalesShippingMethods SalesShippingMethods(array $attributes = [])
 * @method \Picqer\Financials\Exact\Schedule Schedule(array $attributes = [])
 * @method \Picqer\Financials\Exact\SerialNumber SerialNumber(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShippingMethod ShippingMethod(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrder ShopOrder(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrderMaterialPlan ShopOrderMaterialPlan(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrderMaterialPlanDetail ShopOrderMaterialPlanDetail(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrderPriority ShopOrderPriority(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrderReceipt ShopOrderReceipt(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrderReversal ShopOrderReversal(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrderRoutingStepPlan ShopOrderRoutingStepPlan(array $attributes = [])
 * @method \Picqer\Financials\Exact\ShopOrderRoutingStepPlansAvailableToWork ShopOrderRoutingStepPlansAvailableToWork(array $attributes = [])
 * @method \Picqer\Financials\Exact\SolutionLink SolutionLink(array $attributes = [])
 * @method \Picqer\Financials\Exact\StageForDeliveryReceipt StageForDeliveryReceipt(array $attributes = [])
 * @method \Picqer\Financials\Exact\StageForDeliveryReversal StageForDeliveryReversal(array $attributes = [])
 * @method \Picqer\Financials\Exact\StartedTimedTimeTransaction StartedTimedTimeTransaction(array $attributes = [])
 * @method \Picqer\Financials\Exact\StockBatchNumber StockBatchNumber(array $attributes = [])
 * @method \Picqer\Financials\Exact\StockCount StockCount(array $attributes = [])
 * @method \Picqer\Financials\Exact\StockCountLine StockCountLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\StockPosition StockPosition(array $attributes = [])
 * @method \Picqer\Financials\Exact\StockSerialNumber StockSerialNumber(array $attributes = [])
 * @method \Picqer\Financials\Exact\StorageLocation StorageLocation(array $attributes = [])
 * @method \Picqer\Financials\Exact\SubOrderReversal SubOrderReversal(array $attributes = [])
 * @method \Picqer\Financials\Exact\Subscription Subscription(array $attributes = [])
 * @method \Picqer\Financials\Exact\SubscriptionLine SubscriptionLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\SubscriptionReasonCode SubscriptionReasonCode(array $attributes = [])
 * @method \Picqer\Financials\Exact\SubscriptionRestrictionEmployee SubscriptionRestrictionEmployee(array $attributes = [])
 * @method \Picqer\Financials\Exact\SubscriptionRestrictionItem SubscriptionRestrictionItem(array $attributes = [])
 * @method \Picqer\Financials\Exact\SubscriptionType SubscriptionType(array $attributes = [])
 * @method \Picqer\Financials\Exact\SupplierItem SupplierItem(array $attributes = [])
 * @method \Picqer\Financials\Exact\SystemDivision SystemDivision(array $attributes = [])
 * @method \Picqer\Financials\Exact\Task Task(array $attributes = [])
 * @method \Picqer\Financials\Exact\TaskType TaskType(array $attributes = [])
 * @method \Picqer\Financials\Exact\TaxEmploymentEndFlexCode TaxEmploymentEndFlexCode(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingAccountDetail TimeAndBillingAccountDetail(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingAccountDetailsByID TimeAndBillingAccountDetailsByID(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingActivitiesAndExpense TimeAndBillingActivitiesAndExpense(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryAccount TimeAndBillingEntryAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryAccountsByDate TimeAndBillingEntryAccountsByDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryAccountsByProjectAndDate TimeAndBillingEntryAccountsByProjectAndDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryProject TimeAndBillingEntryProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryProjectsByAccountAndDate TimeAndBillingEntryProjectsByAccountAndDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryProjectsByDate TimeAndBillingEntryProjectsByDate(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryRecentAccount TimeAndBillingEntryRecentAccount(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryRecentActivitiesAndExpense TimeAndBillingEntryRecentActivitiesAndExpense(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryRecentHourCostType TimeAndBillingEntryRecentHourCostType(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingEntryRecentProject TimeAndBillingEntryRecentProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingItemDetail TimeAndBillingItemDetail(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingItemDetailsByID TimeAndBillingItemDetailsByID(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingProjectDetail TimeAndBillingProjectDetail(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingProjectDetailsByID TimeAndBillingProjectDetailsByID(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeAndBillingRecentProject TimeAndBillingRecentProject(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeCorrection TimeCorrection(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimeTransaction TimeTransaction(array $attributes = [])
 * @method \Picqer\Financials\Exact\TimedTimeTransaction TimedTimeTransaction(array $attributes = [])
 * @method \Picqer\Financials\Exact\Transaction Transaction(array $attributes = [])
 * @method \Picqer\Financials\Exact\TransactionLine TransactionLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\Unit Unit(array $attributes = [])
 * @method \Picqer\Financials\Exact\Units Units(array $attributes = [])
 * @method \Picqer\Financials\Exact\User User(array $attributes = [])
 * @method \Picqer\Financials\Exact\UserHasRights UserHasRights(array $attributes = [])
 * @method \Picqer\Financials\Exact\UserRole UserRole(array $attributes = [])
 * @method \Picqer\Financials\Exact\UserRolesPerDivision UserRolesPerDivision(array $attributes = [])
 * @method \Picqer\Financials\Exact\VatCode VatCode(array $attributes = [])
 * @method \Picqer\Financials\Exact\VatPercentage VatPercentage(array $attributes = [])
 * @method \Picqer\Financials\Exact\Warehouse Warehouse(array $attributes = [])
 * @method \Picqer\Financials\Exact\WarehouseTransfer WarehouseTransfer(array $attributes = [])
 * @method \Picqer\Financials\Exact\WarehouseTransferLine WarehouseTransferLine(array $attributes = [])
 * @method \Picqer\Financials\Exact\WebhookSubscription WebhookSubscription(array $attributes = [])
 * @method \Picqer\Financials\Exact\Workcenter Workcenter(array $attributes = [])
 * @method self connectionSetBaseUrl(string $baseUrl)
 * @method self setApiUrl(string $apiUrl)
 * @method self setAuthUrl(string $authUrl)
 * @method self setTokenUrl(string $tokenUrl)
 *
 * Class LaravelExactOnline
 */
class LaravelExactOnline
{
    /** @var null|Lock */
    public static $lock = null;

    /** @var Connection */
    private $connection;

    /** @var string */
    private static $lockKey = 'exactonline.refreshLock';

    /**
     * Return connection instance.
     */
    public function connection(): Connection
    {
        if (! $this->connection) {
            $this->connection = app()->make('Exact\Connection');
        }
        return $this->connection;
    }

    /**
     * Function to handle the token update call from picqer.
     *
     * @param Connection $connection Connection instance.
     */
    public static function tokenUpdateCallback(Connection $connection): void
    {
        $config = self::loadConfig();

        $config->exact_accessToken = serialize($connection->getAccessToken());
        $config->exact_refreshToken = $connection->getRefreshToken();
        $config->exact_tokenExpires = $connection->getTokenExpires();

        self::storeConfig($config);
    }

    /**
     * Function to handle the token refresh call from picqer.
     *
     * @param Connection $connection Connection instance.
     */
    public static function tokenRefreshCallback(Connection $connection): void
    {
        $config = self::loadConfig();

        if (isset($config->exact_accessToken)) {
            $connection->setAccessToken(unserialize($config->exact_accessToken));
        }
        if (isset($config->exact_refreshToken)) {
            $connection->setRefreshToken($config->exact_refreshToken);
        }
        if (isset($config->exact_tokenExpires)) {
            $connection->setTokenExpires($config->exact_tokenExpires);
        }
    }

    /**
     * Acquire refresh lock to avoid duplicate calls to exact.
     */
    public static function acquireLock(): bool
    {
        /** @var Repository $cache */
        $cache = app()->make(Repository::class);
        $store = $cache->getStore();

        if (! $store instanceof LockProvider) {
            return false;
        }

        self::$lock = $store->lock(self::$lockKey, 60);
        return self::$lock->block(30);
    }

    /**
     * Release lock that was set.
     *
     * @return bool
     */
    public static function releaseLock()
    {
        return optional(self::$lock)->release();
    }

    /**
     * Load existing configuration.
     *
     * @return Authenticatable|stdClass
     */
    public static function loadConfig()
    {
        if (config('laravel-exact-online.exact_multi_user')) {
            return Auth::user();
        }

        $config = '{}';

        $disk = config('laravel-exact-online.exact-api-storage');
        if (Storage::disk($disk)->exists('exact.api.json')) {
            $config = Storage::disk($disk)->get(
                'exact.api.json',
            );
        }

        return (object) json_decode($config, false);
    }

    /**
     * Store configuration changes.
     *
     * @param Authenticatable|stdClass $config
     */
    public static function storeConfig($config): void
    {
        if (config('laravel-exact-online.exact_multi_user')) {
            $config->save();
            return;
        }
        $disk = config('laravel-exact-online.exact-api-storage');
        Storage::disk($disk)->put('exact.api.json', json_encode($config));
    }

    /**
     * Magically calls methods from Picqer Exact Online API
     *
     * @param string $method Name of the method that's called.
     * @param array $arguments Arguments passed to it.
     *
     * @return mixed
     *
     * @throws RuntimeException Throws a RuntimeException when the provided method does not exist.
     */
    public function __call($method, $arguments)
    {
        if (strpos($method, "connection") === 0) {
            $method = lcfirst(substr($method, 10));

            call_user_func([$this->connection(), $method], implode(",", $arguments));

            return $this;
        }

        $classname = "\\Picqer\\Financials\\Exact\\" . $method;

        if (class_exists($classname) === false) {
            throw new RuntimeException("Invalid type called");
        }

        // Extract attributes to pass to the model instance.
        $attributes = count($arguments) !== 0 ? $arguments[0] : [];

        return new $classname($this->connection(), $attributes);
    }
}
