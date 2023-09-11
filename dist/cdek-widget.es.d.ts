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
    components: iGeocoderComponent[];
}

declare interface iOffice {
    city_code: number;
    type: OfficeType;
    country_code: string;
    have_cashless: boolean;
    have_cash: boolean;
    allowed_cod: boolean;
    is_dressing_room: boolean;
    code: string;
    name: string;
    address: string;
    work_time: string;
    location: LngLat;
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

declare const enum OfficeType {
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

declare type tChooseFunction = (type: DeliveryMode, tariff: iTariff, target: iOffice | iGeocoderMember) => void;

declare type tReadyFunction = () => void;

declare class Widget {
    private readonly yandexMapSrc;
    private readonly geocodeSrc;
    private readonly params;
    private geocodeStringAbort;
    private geocodeCoordinatesAbort;
    private getOfficesAbort;
    private getPriceAbort;
    constructor(input: iWidget);
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
    private init;
    private getPrice;
    private getOffices;
    private formatGeocodeResponse;
    private geocodeString;
    private geocodeCoordinates;
    private cancelRequest;
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
    hideDeliveryOptions: {
        office: boolean;
        courier: boolean;
    };
    debug: boolean;
    goods: {
        width: number;
        length: number;
        height: number;
        weight: number;
    }[];
    from: string;
    defaultLocation: NonNullable<string | LngLat | undefined>;
    lang: Lang;
    currency: string;
    tariffs: {
        door: any[];
        office: any[];
    };
    onReady: tReadyFunction | undefined;
    onCalculate: tCalculateFunction | undefined;
    onChoose: tChooseFunction | undefined;
}, AnyObject, {
    apiKey: any;
    root: "app";
    sender: false;
    canChoose: true;
    popup: false;
    servicePath: "/service";
    hideFilters: {
        have_cashless: false;
        have_cash: false;
        is_dressing_room: false;
        type: false;
    };
    hideDeliveryOptions: {
        office: false;
        courier: false;
    };
    debug: false;
    goods: "d";
    from: undefined;
    defaultLocation: undefined;
    lang: Lang.RUS;
    currency: "RUB";
    tariffs: {
        office: number[];
        door: number[];
    };
    onReady: Maybe<tReadyFunction | undefined>;
    onCalculate: Maybe<tCalculateFunction | undefined>;
    onChoose: Maybe<tChooseFunction | undefined>;
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
