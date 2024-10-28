(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
	typeof define === 'function' && define.amd ? define(['exports'], factory) :
	(global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.CKEDITOR = {}));
})(this, (function (exports) { 'use strict';

	/**
	 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
	 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
	 */

	exports.AccessibilityHelp = qf;
	exports.AdjacentListsSupport = CV;
	exports.Alignment = m_;
	exports.AlignmentEditing = Gg;
	exports.AlignmentUI = u_;
	exports.AttributeElement = $l;
	exports.AttributeOperation = Gh;
	exports.AutoImage = ME;
	exports.AutoLink = $S;
	exports.AutoMediaEmbed = pB;
	exports.AutocompleteView = qw;
	exports.Autoformat = tv;
	exports.Autosave = nv;
	exports.BalloonEditor = SC;
	exports.BalloonPanelView = qb;
	exports.BalloonToolbar = Yw;
	exports.Base64UploadAdapter = Rg;
	exports.BlockQuote = ny;
	exports.BlockQuoteEditing = ty;
	exports.BlockQuoteUI = iy;
	exports.BlockToolbar = i_;
	exports.BodyCollection = Gf;
	exports.Bold = dv;
	exports.BoldEditing = rv;
	exports.BoldUI = cv;
	exports.BoxedEditorUIView = Iw;
	exports.BubblingEventInfo = Ol;
	exports.ButtonLabelView = xf;
	exports.ButtonLabelWithHighlightView = Kw;
	exports.ButtonView = Af;
	exports.CKBox = Vy;
	exports.CKBoxEditing = Ey;
	exports.CKBoxImageEdit = Ly;
	exports.CKBoxImageEditEditing = Oy;
	exports.CKBoxImageEditUI = My;
	exports.CKBoxUI = sy;
	exports.CKEditorError = E;
	exports.CKFinder = Hy;
	exports.CKFinderEditing = zy;
	exports.CKFinderUI = Fy;
	exports.CKFinderUploadAdapter = Dg;
	exports.ClassicEditor = RC;
	exports.ClickObserver = Qu;
	exports.Clipboard = Zk;
	exports.ClipboardMarkersUtils = Gy;
	exports.ClipboardPipeline = Ky;
	exports.CloudServices = nC;
	exports.CloudServicesCore = iC;
	exports.CloudServicesUploadAdapter = CC;
	exports.Code = fv;
	exports.CodeBlock = kC;
	exports.CodeBlockEditing = wC;
	exports.CodeBlockUI = yC;
	exports.CodeEditing = uv;
	exports.CodeUI = gv;
	exports.CollapsibleView = ep;
	exports.Collection = Ta;
	exports.ColorGridView = op;
	exports.ColorPickerView = Rb;
	exports.ColorSelectorView = Hb;
	exports.ColorTileView = sp;
	exports.Command = Ka;
	exports.ComponentFactory = $b;
	exports.Config = _r;
	exports.Context = Ya;
	exports.ContextPlugin = Xa;
	exports.ContextWatchdog = gg;
	exports.ContextualBalloon = Fw;
	exports.Conversion = Ph;
	exports.CssTransitionDisablerMixin = vf;
	exports.DataApiMixin = xg;
	exports.DataController = Ih;
	exports.DataFilter = iE;
	exports.DataSchema = tE;
	exports.DataTransfer = Uc;
	exports.DecoupledEditor = LC;
	exports.DefaultMenuBarItems = mw;
	exports.Delete = P_;
	exports.Dialog = Nf;
	exports.DialogView = Ff;
	exports.DialogViewPosition = Mf;
	exports.DocumentFragment = Su;
	exports.DocumentList = AV;
	exports.DocumentListProperties = EV;
	exports.DocumentSelection = Td;
	exports.DomConverter = Ic;
	exports.DomEmitterMixin = Ar;
	exports.DomEventData = Oc;
	exports.DomEventObserver = Mc;
	exports.DomWrapperView = VB;
	exports.DowncastWriter = Xl;
	exports.DragDrop = Wk;
	exports.DragDropBlockToolbar = Uk;
	exports.DragDropTarget = Fk;
	exports.DropdownButtonView = Bp;
	exports.DropdownPanelView = Vp;
	exports.DropdownView = Rp;
	exports.EasyImage = AC;
	exports.EditingController = ah;
	exports.EditingView = Kc;
	exports.Editor = kg;
	exports.EditorUI = Ew;
	exports.EditorUIView = Sw;
	exports.EditorWatchdog = hg;
	exports.Element = ed;
	exports.ElementApiMixin = Ag;
	exports.ElementReplacer = mr;
	exports.EmitterMixin = F;
	exports.Enter = qv;
	exports.Essentials = ax;
	exports.EventInfo = v;
	exports.FileDialogButtonView = Zf;
	exports.FileDialogListItemButtonView = Jf;
	exports.FileRepository = Pg;
	exports.FindAndReplace = yx;
	exports.FindAndReplaceEditing = vx;
	exports.FindAndReplaceUI = dx;
	exports.FindAndReplaceUtils = wx;
	exports.FindCommand = hx;
	exports.FindNextCommand = fx;
	exports.FindPreviousCommand = px;
	exports.FocusCycler = Sf;
	exports.FocusObserver = zc;
	exports.FocusTracker = Ia;
	exports.Font = iA;
	exports.FontBackgroundColor = tA;
	exports.FontBackgroundColorEditing = Xx;
	exports.FontBackgroundColorUI = eA;
	exports.FontColor = Qx;
	exports.FontColorEditing = Kx;
	exports.FontColorUI = Jx;
	exports.FontFamily = Fx;
	exports.FontFamilyEditing = Ox;
	exports.FontFamilyUI = Lx;
	exports.FontSize = qx;
	exports.FontSizeEditing = Ux;
	exports.FontSizeUI = jx;
	exports.FormHeaderView = Tf;
	exports.FullPage = AE;
	exports.GeneralHtmlSupport = yE;
	exports.Heading = gA;
	exports.HeadingButtonsUI = pA;
	exports.HeadingEditing = hA;
	exports.HeadingUI = mA;
	exports.Highlight = PA;
	exports.HighlightEditing = TA;
	exports.HighlightUI = SA;
	exports.HighlightedTextView = Gw;
	exports.History = vu;
	exports.HorizontalLine = OA;
	exports.HorizontalLineEditing = RA;
	exports.HorizontalLineUI = BA;
	exports.HtmlComment = CE;
	exports.HtmlDataProcessor = Sh;
	exports.HtmlEmbed = zA;
	exports.HtmlEmbedEditing = FA;
	exports.HtmlEmbedUI = DA;
	exports.HtmlPageDataProcessor = xE;
	exports.IconView = Cf;
	exports.IframeView = Rw;
	exports.Image = nT;
	exports.ImageBlock = eT;
	exports.ImageBlockEditing = QE;
	exports.ImageCaption = lT;
	exports.ImageCaptionEditing = rT;
	exports.ImageCaptionUI = aT;
	exports.ImageCaptionUtils = sT;
	exports.ImageCustomResizeUI = zT;
	exports.ImageEditing = GE;
	exports.ImageInline = iT;
	exports.ImageInsert = ET;
	exports.ImageInsertUI = XE;
	exports.ImageInsertViaUrl = AT;
	exports.ImageResize = HT;
	exports.ImageResizeButtons = PT;
	exports.ImageResizeEditing = ST;
	exports.ImageResizeHandles = MT;
	exports.ImageSizeAttributes = KE;
	exports.ImageStyle = eS;
	exports.ImageStyleEditing = ZT;
	exports.ImageStyleUI = JT;
	exports.ImageTextAlternative = HE;
	exports.ImageTextAlternativeEditing = FE;
	exports.ImageTextAlternativeUI = zE;
	exports.ImageToolbar = tS;
	exports.ImageUpload = kT;
	exports.ImageUploadEditing = vT;
	exports.ImageUploadProgress = mT;
	exports.ImageUploadUI = uT;
	exports.ImageUtils = RE;
	exports.Indent = oS;
	exports.IndentBlock = dS;
	exports.IndentEditing = nS;
	exports.IndentUI = sS;
	exports.InlineEditableUIView = Vw;
	exports.InlineEditor = HC;
	exports.Input = __;
	exports.InputNumberView = Sp;
	exports.InputTextView = Tp;
	exports.InputView = Ep;
	exports.InsertOperation = Uh;
	exports.InsertTextCommand = f_;
	exports.Italic = vv;
	exports.ItalicEditing = bv;
	exports.ItalicUI = _v;
	exports.KeystrokeHandler = Pa;
	exports.LabelView = $f;
	exports.LabelWithHighlightView = Zw;
	exports.LabeledFieldView = xp;
	exports.LegacyIndentCommand = EP;
	exports.LegacyList = tV;
	exports.LegacyListEditing = XP;
	exports.LegacyListProperties = uV;
	exports.LegacyListPropertiesEditing = rV;
	exports.LegacyListUtils = DP;
	exports.LegacyTodoList = kV;
	exports.LegacyTodoListEditing = vV;
	exports.Link = WS;
	exports.LinkCommand = SS;
	exports.LinkEditing = OS;
	exports.LinkImage = ZS;
	exports.LinkImageEditing = jS;
	exports.LinkImageUI = KS;
	exports.LinkUI = DS;
	exports.List = $I;
	exports.ListCommand = vI;
	exports.ListEditing = FI;
	exports.ListIndentCommand = wI;
	exports.ListItemButtonView = Df;
	exports.ListItemGroupView = Wp;
	exports.ListItemView = $p;
	exports.ListProperties = uP;
	exports.ListPropertiesEditing = oP;
	exports.ListPropertiesUI = aP;
	exports.ListPropertiesUtils = tP;
	exports.ListSeparatorView = Up;
	exports.ListUI = HI;
	exports.ListUtils = CI;
	exports.ListView = jp;
	exports.LivePosition = hu;
	exports.LiveRange = Cd;
	exports.Locale = Ea;
	exports.MSWordNormalizer = bO;
	exports.Markdown = nB;
	exports.MarkerOperation = qh;
	exports.Matcher = gl;
	exports.MediaEmbed = _B;
	exports.MediaEmbedEditing = gB;
	exports.MediaEmbedToolbar = vB;
	exports.MediaEmbedUI = wB;
	exports.Mention = $B;
	exports.MentionEditing = CB;
	exports.MentionListItemView = RB;
	exports.MentionUI = MB;
	exports.MentionsView = PB;
	exports.MenuBarMenuListItemButtonView = Hf;
	exports.MenuBarMenuListItemFileDialogButtonView = l_;
	exports.MenuBarMenuListItemView = cw;
	exports.MenuBarMenuListView = r_;
	exports.MenuBarMenuView = o_;
	exports.MenuBarView = d_;
	exports.MergeOperation = jh;
	exports.Minimap = QB;
	exports.Model = Zu;
	exports.MouseObserver = Yu;
	exports.MoveOperation = $h;
	exports.MultiCommand = Ja;
	exports.MultiRootEditor = jC;
	exports.NoOperation = Kh;
	exports.Notification = Ow;
	exports.ObservableMixin = ar;
	exports.Observer = Bc;
	exports.OperationFactory = Xh;
	exports.PageBreak = tO;
	exports.PageBreakEditing = XB;
	exports.PageBreakUI = eO;
	exports.Paragraph = rA;
	exports.ParagraphButtonUI = aA;
	exports.PasteFromMarkdownExperimental = oB;
	exports.PasteFromOffice = EO;
	exports.PastePlainText = Kk;
	exports.PendingActions = Tg;
	exports.PictureEditing = iS;
	exports.PlainTableOutput = fF;
	exports.Plugin = qa;
	exports.Position = nd;
	exports.Range = cd;
	exports.Rect = Lr;
	exports.RemoveFormat = VO;
	exports.RemoveFormatEditing = PO;
	exports.RemoveFormatUI = SO;
	exports.RenameOperation = Zh;
	exports.Renderer = _c;
	exports.ReplaceAllCommand = gx;
	exports.ReplaceCommand = mx;
	exports.ResizeObserver = Hr;
	exports.RestrictedEditingMode = jO;
	exports.RestrictedEditingModeEditing = zO;
	exports.RestrictedEditingModeUI = WO;
	exports.RootAttributeOperation = Jh;
	exports.RootOperation = Qh;
	exports.SearchInfoView = Uw;
	exports.SearchTextView = jw;
	exports.SelectAll = YC;
	exports.SelectAllEditing = JC;
	exports.SelectAllUI = QC;
	exports.ShiftEnter = Jv;
	exports.ShowBlocks = XO;
	exports.ShowBlocksCommand = JO;
	exports.ShowBlocksEditing = QO;
	exports.ShowBlocksUI = YO;
	exports.SimpleUploadAdapter = Og;
	exports.SourceEditing = nM;
	exports.SpecialCharacters = hM;
	exports.SpecialCharactersArrows = uM;
	exports.SpecialCharactersCurrency = pM;
	exports.SpecialCharactersEssentials = bM;
	exports.SpecialCharactersLatin = fM;
	exports.SpecialCharactersMathematical = gM;
	exports.SpecialCharactersText = mM;
	exports.SpinnerView = Jw;
	exports.SplitButtonView = qp;
	exports.SplitOperation = Wh;
	exports.StandardEditingMode = ZO;
	exports.StandardEditingModeEditing = GO;
	exports.StandardEditingModeUI = KO;
	exports.StickyPanelView = Hw;
	exports.Strikethrough = Av;
	exports.StrikethroughEditing = kv;
	exports.StrikethroughUI = xv;
	exports.Style = BM;
	exports.StyleEditing = RM;
	exports.StyleUI = AM;
	exports.StyleUtils = CM;
	exports.StylesMap = bl;
	exports.StylesProcessor = wl;
	exports.Subscript = Pv;
	exports.SubscriptEditing = Tv;
	exports.SubscriptUI = Iv;
	exports.Superscript = Mv;
	exports.SuperscriptEditing = Rv;
	exports.SuperscriptUI = Ov;
	exports.SwitchButtonView = Kf;
	exports.TabObserver = Gc;
	exports.Table = gF;
	exports.TableCaption = ON;
	exports.TableCaptionEditing = RN;
	exports.TableCaptionUI = BN;
	exports.TableCellProperties = cN;
	exports.TableCellPropertiesEditing = lN;
	exports.TableCellPropertiesUI = KF;
	exports.TableCellWidthEditing = QF;
	exports.TableClipboard = rF;
	exports.TableColumnResize = NN;
	exports.TableColumnResizeEditing = FN;
	exports.TableEditing = eF;
	exports.TableKeyboard = dF;
	exports.TableMouse = uF;
	exports.TableProperties = EN;
	exports.TablePropertiesEditing = vN;
	exports.TablePropertiesUI = AN;
	exports.TableSelection = oF;
	exports.TableToolbar = yF;
	exports.TableUI = nF;
	exports.TableUtils = BL;
	exports.Template = Zg;
	exports.Text = Yc;
	exports.TextPartLanguage = fS;
	exports.TextPartLanguageEditing = mS;
	exports.TextPartLanguageUI = gS;
	exports.TextProxy = Xc;
	exports.TextTransformation = W_;
	exports.TextWatcher = B_;
	exports.TextareaView = Ip;
	exports.Title = wA;
	exports.TodoDocumentList = TV;
	exports.TodoList = kP;
	exports.TodoListEditing = pP;
	exports.TodoListUI = yP;
	exports.ToolbarLineBreakView = Mp;
	exports.ToolbarSeparatorView = Op;
	exports.ToolbarView = Np;
	exports.TooltipManager = Zb;
	exports.TreeWalker = td;
	exports.TwoStepCaretMovement = O_;
	exports.Typing = V_;
	exports.Underline = zv;
	exports.UnderlineEditing = Fv;
	exports.UnderlineUI = Dv;
	exports.Undo = rx;
	exports.UndoEditing = sx;
	exports.UndoUI = ox;
	exports.UnlinkCommand = PS;
	exports.UpcastWriter = Xu;
	exports.View = bf;
	exports.ViewAttributeElement = $l;
	exports.ViewCollection = Kg;
	exports.ViewContainerElement = Cl;
	exports.ViewDocument = Hl;
	exports.ViewDocumentFragment = Yl;
	exports.ViewEditableElement = Al;
	exports.ViewElement = yl;
	exports.ViewEmptyElement = jl;
	exports.ViewModel = Mw;
	exports.ViewRawElement = Jl;
	exports.ViewRootEditableElement = Tl;
	exports.ViewText = ul;
	exports.ViewTreeWalker = Sl;
	exports.ViewUIElement = Gl;
	exports.WIDGET_CLASS_NAME = ek;
	exports.WIDGET_SELECTED_CLASS_NAME = tk;
	exports.Watchdog = ag;
	exports.Widget = Ak;
	exports.WidgetResize = Ok;
	exports.WidgetToolbarRepository = Tk;
	exports.WidgetTypeAround = yk;
	exports.WordCount = zN;
	exports.XmlDataProcessor = Bh;
	exports._getModelData = Xm;
	exports._getViewData = qm;
	exports._parseModel = ig;
	exports._parseView = Zm;
	exports._setModelData = eg;
	exports._setViewData = Gm;
	exports._stringifyModel = tg;
	exports._stringifyView = Km;
	exports.abortableDebounce = gr;
	exports.addBackgroundRules = Tm;
	exports.addBorderRules = Sm;
	exports.addKeyboardHandlingForGrid = kf;
	exports.addListToDropdown = Jp;
	exports.addMarginRules = Nm;
	exports.addPaddingRules = Dm;
	exports.addToolbarToDropdown = Kp;
	exports.attachToForm = Cg;
	exports.autoParagraphEmptyRoots = Gd;
	exports.blockAutoformatEditing = Y_;
	exports.calculateResizeHostAncestorWidth = mk;
	exports.calculateResizeHostPercentageWidth = gk;
	exports.clickOutsideHandler = wf;
	exports.compareArrays = pr;
	exports.count = fr;
	exports.createDropdown = Gp;
	exports.createElement = wr;
	exports.createLabeledDropdown = nb;
	exports.createLabeledInputNumber = tb;
	exports.createLabeledInputText = eb;
	exports.createLabeledTextarea = ib;
	exports.delay = La;
	exports.diff = b;
	exports.diffToChanges = w;
	exports.disablePlaceholder = nl;
	exports.enablePlaceholder = il;
	exports.env = o;
	exports.exponentialDelay = Oa;
	exports.fastDiff = g;
	exports.filterGroupAndItemNames = Bw;
	exports.findAttributeRange = Z_;
	exports.findAttributeRangeBound = J_;
	exports.findClosestScrollableAncestor = Sr;
	exports.findOptimalInsertionRange = dk;
	exports.first = Sa;
	exports.focusChildOnDropdownOpen = Yp;
	exports.getAncestors = Ir;
	exports.getBorderWidths = Vr;
	exports.getBoxSidesShorthandValue = xm;
	exports.getBoxSidesValueReducer = Cm;
	exports.getBoxSidesValues = km;
	exports.getCode = fa;
	exports.getDataFromElement = Pr;
	exports.getEnvKeystrokeText = ba;
	exports.getFillerOffset = xl;
	exports.getLabel = lk;
	exports.getLanguageDirection = Ca;
	exports.getLastTextLine = R_;
	exports.getLocalizedArrowKeyCodeDirection = _a;
	exports.getLocalizedColorOptions = tp;
	exports.getOptimalPosition = Zr;
	exports.getPositionShorthandNormalizer = Am;
	exports.getShorthandValues = Em;
	exports.global = i;
	exports.hidePlaceholder = ol;
	exports.icons = Sg;
	exports.indexOf = Wr;
	exports.injectCssTransitionDisabler = _f;
	exports.inlineAutoformatEditing = X_;
	exports.inlineHighlight = Q_;
	exports.insertAt = jr;
	exports.insertToPriorityArray = x;
	exports.isArrowKeyCode = wa;
	exports.isAttachment = _m;
	exports.isColor = am;
	exports.isCombiningMark = Na;
	exports.isComment = qr;
	exports.isFocusable = Pf;
	exports.isForwardArrowKeyCode = va;
	exports.isHighSurrogateHalf = Da;
	exports.isInsideCombinedSymbol = $a;
	exports.isInsideEmojiSequence = Wa;
	exports.isInsideSurrogatePair = Ha;
	exports.isIterable = br;
	exports.isLength = hm;
	exports.isLineStyle = cm;
	exports.isLowSurrogateHalf = za;
	exports.isNode = kr;
	exports.isParagraphable = Kd;
	exports.isPercentage = mm;
	exports.isPosition = bm;
	exports.isRange = Br;
	exports.isRepeat = fm;
	exports.isText = Rr;
	exports.isURL = ym;
	exports.isValidAttributeName = Gr;
	exports.isViewWithFocusCycler = Vf;
	exports.isVisible = Kr;
	exports.isWidget = ik;
	exports.keyCodes = ma;
	exports.logError = S;
	exports.logWarning = T;
	exports.mix = _;
	exports.needsPlaceholder = rl;
	exports.normalizeColorOptions = ip;
	exports.normalizeMenuBarConfig = gw;
	exports.normalizeSingleColorDefinition = np;
	exports.normalizeToolbarConfig = Lp;
	exports.parseHtml = AO;
	exports.parseKeystroke = pa;
	exports.priorities = C;
	exports.releaseDate = R;
	exports.remove = Yr;
	exports.retry = Ba;
	exports.scrollAncestorsToShowTarget = ea;
	exports.scrollViewportToShowTarget = Xr;
	exports.secureSourceElement = Eg;
	exports.setDataInElement = $r;
	exports.setHighlightHandling = rk;
	exports.setLabel = ak;
	exports.showPlaceholder = sl;
	exports.spliceArray = Ma;
	exports.submitHandler = yf;
	exports.toArray = xa;
	exports.toMap = Va;
	exports.toUnit = Ur;
	exports.toWidget = nk;
	exports.toWidgetEditable = ck;
	exports.transformSets = su;
	exports.uid = k;
	exports.verifyLicense = Fa;
	exports.version = V;
	exports.viewToModelPositionOutsideModelElement = hk;
	exports.wait = Ra;
	exports.wrapInParagraph = Zd;

}));
//# sourceMappingURL=ckeditor5.umd.js.map