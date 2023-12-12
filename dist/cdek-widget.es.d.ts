import { AnyObject } from 'yup';
import { InferType } from 'yup';
import { LngLat } from '@yandex/ymaps3-types/common/types';
import { Maybe } from 'yup';
import { ObjectSchema } from 'yup';

declare const enum CdekDeliveryType {
    DOOR_DOOR = 1,
    DOOR_OFFICE = 2,
    OFFICE_DOOR = 3,
    OFFICE_OFFICE = 4,
    DOOR_PICKUP = 6,
    OFFICE_PICKUP = 7,
    PICKUP_DOOR = 8,
    PICKUP_OFFICE = 9,
    PICKUP_PICKUP = 10
}

declare const enum DeliveryMode {
    DOOR = "door",
    OFFICE = "office"
}

declare interface iGeocoderComponent {
    name: string;
    kind: YandexGeocoderKind;
}

declare interface iGeocoderMember {
    name: string;
    position: number[];
    kind: YandexGeocoderKind;
    precision: YandexGeocoderPrecision;
    formatted: string;
    country_code: string;
    postal_code: string | null;
    components: iGeocoderComponent[];
    bounds: {
        lower: number[];
        upper: number[];
    };
}

declare interface iOffice {
    city_code: number;
    city: string;
    region: string;
    type: OfficeType;
    country_code: string;
    postal_code: string;
    have_cashless: boolean;
    have_cash: boolean;
    allowed_cod: boolean;
    is_dressing_room: boolean;
    code: string;
    name: string;
    address: string;
    work_time: string;
    location: LngLat;
    dimensions: Array<{
        depth: number;
        width: number;
        height: number;
    }> | null;
    weight_min: number;
    weight_max: number;
}

declare interface iParcel {
    length: number;
    width: number;
    height: number;
    weight: number;
}

declare interface iTariff {
    tariff_code: number;
    tariff_name: string;
    tariff_description: string;
    delivery_mode: CdekDeliveryType;
    period_min: number;
    period_max: number;
    delivery_sum: number;
}

declare interface iWidget extends InferType<typeof widgetSchema> {
    goods: iParcel[];
    offices: iOffice[] | null;
    defaultLocation: string | LngLat;
    lang: Lang;
    onCalculate?: tCalculateFunction;
    onReady?: tReadyFunction;
    onChoose?: tChooseFunction;
}

declare enum Lang {
    RUS = "rus",
    ENG = "eng"
}

declare enum OfficeType {
    ALL = "ALL",
    OFFICE = "PVZ",
    PICKUP = "POSTAMAT"
}

declare type tCalculateFunction = (prices: {
    office: iTariff[];
    door: iTariff[];
    pickup: iTariff[];
}, address: {
    code?: number;
    address?: string;
}) => void;

declare type tChooseFunction = (type: DeliveryMode, tariff: iTariff | null, target: iOffice | iGeocoderMember) => void;

declare type tReadyFunction = () => void;

declare class Widget {
    private readonly params;
    private readonly yandexApi;
    private readonly cdekApi;
    private readonly app;
    private readonly div;
    private readonly customDiv;
    constructor(input: iWidget);
    updateOffices(offices: iOffice[]): Promise<void>;
    updateOfficesRaw(officesRaw: any): Promise<void>;
    updateLocation(location: any): Promise<void>;
    updateTariff(tariff: iTariff): Promise<void>;
    clearSelection(): void;
    destroy(): void;
    open(): void;
    close(): void;
    addParcel(parcel: iParcel | iParcel[]): void;
    getParcels(): {
        length: number;
        width: number;
        height: number;
        weight: number;
    }[];
    resetParcels(): void;
    private fixBounds;
    private init;
}
export default Widget;

declare const widgetSchema: ObjectSchema<{
    apiKey: string;
    root: string;
    sender: boolean;
    canChoose: boolean;
    popup: boolean;
    servicePath: string;
    hideFilters: {
        have_cashless: boolean;
        have_cash: boolean;
        is_dressing_room: boolean;
        type: boolean;
    };
    forceFilters: {
        have_cashless: boolean | null;
        have_cash: boolean | null;
        is_dressing_room: boolean | null;
        type: OfficeType | null;
        allowed_cod: boolean | null;
    };
    hideDeliveryOptions: {
        door: boolean;
        office: boolean;
    };
    debug: boolean;
    requirePostcode: boolean;
    fixBounds: YandexGeocoderKind.COUNTRY | YandexGeocoderKind.PROVINCE | YandexGeocoderKind.LOCALITY | null;
    offices: any[] | null;
    officesRaw: any[] | null;
    tariff: {
        tariff_code?: number | undefined;
        tariff_name?: string | undefined;
        tariff_description?: string | undefined;
        delivery_mode?: number | undefined;
        period_min?: number | undefined;
        period_max?: number | undefined;
        delivery_sum?: number | undefined;
    } | null;
    goods: {
        width: number;
        length: number;
        height: number;
        weight: number;
    }[];
    from: string | {
        code: number | null;
        postal_code: string | null;
        country_code: string | null;
        city: string | null;
        address: string | null;
    } | null;
    defaultLocation: NonNullable<string | LngLat | undefined>;
    lang: Lang;
    currency: string;
    tariffs: {
        door: any[];
        office: any[];
        pickup: any[];
    };
    onReady: tReadyFunction | undefined;
    onCalculate: tCalculateFunction | undefined;
    onChoose: tChooseFunction | undefined;
    selected: {
        door: string | null;
        office: string | null;
    };
}, AnyObject, {
    apiKey: any;
    root: "cdek-map";
    sender: false;
    canChoose: true;
    popup: false;
    servicePath: "/service.php";
    hideFilters: {
        have_cashless: false;
        have_cash: false;
        is_dressing_room: false;
        type: false;
    };
    forceFilters: {
        have_cashless: null;
        have_cash: null;
        is_dressing_room: null;
        type: null;
        allowed_cod: null;
    };
    hideDeliveryOptions: {
        office: false;
        door: false;
    };
    debug: false;
    requirePostcode: false;
    fixBounds: null;
    offices: null;
    officesRaw: null;
    tariff: null;
    goods: "d";
    from: undefined;
    defaultLocation: undefined;
    lang: Lang.RUS;
    currency: "RUB";
    tariffs: {
        door: number[];
        office: number[];
        pickup: number[];
    };
    onReady: Maybe<tReadyFunction | undefined>;
    onCalculate: Maybe<tCalculateFunction | undefined>;
    onChoose: Maybe<tChooseFunction | undefined>;
    selected: {
        door: null;
        office: null;
    };
}, "">;

declare const enum YandexGeocoderKind {
    OTHER = "other",
    ENTRANCE = "entrance",
    AIRPORT = "airport",
    VEGETATION = "vegetation",
    ROUTE = "route",
    STATION = "station",
    RAILWAY = "railway_station",
    HYDRO = "hydro",
    REGION = "region",
    COUNTRY = "country",
    PROVINCE = "province",
    AREA = "area",
    LOCALITY = "locality",
    DISTRICT = "district",
    METRO = "metro",
    STREET = "street",
    HOUSE = "house"
}

declare const enum YandexGeocoderPrecision {
    OTHER = "other",
    STREET = "street",
    RANGE = "range",
    NEAR = "near",
    NUMBER = "number",
    EXACT = "exact"
}

export { }
