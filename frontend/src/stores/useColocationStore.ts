import { defineStore } from "pinia";
import axios from "@/services/axios";

export interface Colocation {
  data: {
    type: string;
    id: number;
    attributes: {
      name: string;
      monthly_rent: number;
      max_roommates: number;
    };
    relationships: Relationships;
  };
  isDefined: boolean;
  ownerFirstName: string;
}

interface Relationships {
  owner?: {
    data: {
      type: string;
      id: number;
    };
  };
  roommates?: {
    data: Array<{
      type: string;
      id: number;
    }>;
  };
}

export const useColocationStore = defineStore("colocationStore", {
  state: (): Colocation =>
    (sessionStorage.getItem("colocationStore") as string)
      ? JSON.parse(sessionStorage.getItem("colocationStore") as string)
      : {
          data: {
            type: "",
            id: 0,
            attributes: {
              name: "",
              monthly_rent: 0,
              max_roommates: 0,
            },
            relationships: {},
          },
          isDefined: false,
          ownerFirstName: "",
        },

  getters: {
    getAttributes: (state: Colocation) => state.data.attributes,
    getColocation: (state: Colocation) => state,
    getColocationId: (state: Colocation) => state.data.id,
    colocationIsDefined: (state: Colocation) => state.isDefined,
    getNumberOfRoommates: (state: Colocation) =>
      state.data.relationships.roommates
        ? state.data.relationships.roommates.data.length
        : 0,
    getOwnerFirstName: (state: Colocation) => state.ownerFirstName,
    getOwnerRelationship: (state: Colocation) =>
      state.data.relationships.owner?.data,
  },

  actions: {
    checkOwnership(userId: number) {
      const ownerId = this.data.relationships.owner?.data.id;

      return ownerId === userId;
    },
    async deleteColocation() {
      const response = await axios.delete(`api/colocations/${this.data.id}`);

      return response;
    },
    async fetchColocation(colocationId: number) {
      const response = await axios.get(
        `api/colocations/${colocationId}?include=owner`
      );

      if (response.status === 200) {
        this.setColocation(response.data);

        if (response.data.included.owner !== undefined) {
          this.setOwnerFirstName(
            response.data.included.owner.data.attributes.firstname
          );
        }
      }
    },
    async removeRoommates(Users: Array<number>) {
      const data = Users.map((user_id) => {
        return {
          type: "users",
          id: user_id,
        };
      });

      const body = {
        data: { data },
      };

      const response = await axios.delete(
        `/api/colocations/${this.data.id}/relationships/roommates`,
        body
      );

      if (response.status === 204) {
        Users.forEach((user_id) => {
          const roommateIndex =
            this.data.relationships.roommates?.data.findIndex(
              (roommate: { type: string; id: number }) =>
                roommate.id === user_id
            );

          if (roommateIndex !== undefined) {
            this.data.relationships.roommates?.data.splice(roommateIndex, 1);
          }
        });
      }

      return response;
    },
    async updateColocation(
      updatedAttributes: {
        access_key?: string;
        name?: string;
        monthly_rent?: number;
        max_roommates?: number;
        old_access_key?: string;
      },
      options: { include: Array<string> | null } = { include: null },
      updatedRelationships: Relationships = {}
    ) {
      const body = {
        data: {
          type: "colocations",
          id: this.data.id,
          attributes: updatedAttributes,
          relationships: updatedRelationships,
        },
      };

      let url = `api/colocations/${this.data.id}`;

      if (options.include !== null && options.include.length > 0) {
        options.include.forEach((include) => {
          url += `?include=${include}`;
        });
      }

      const response = await axios.patch(url, JSON.parse(JSON.stringify(body)));

      if (response.status === 200) {
        this.setColocation(response.data);
      }

      return response.data;
    },
    setAttributes(attributes: {
      name: string;
      monthly_rent: number;
      max_roommates: number;
    }) {
      this.data.attributes = attributes;
    },
    setColocation(colocation: Colocation) {
      this.data = colocation.data;
      this.isDefined = true;
    },
    setOwnerFirstName(firstName: string = "") {
      this.ownerFirstName = firstName;
    },
    unSetColocation() {
      this.data = {
        type: "",
        id: 0,
        attributes: {
          name: "",
          monthly_rent: 0,
          max_roommates: 0,
        },
        relationships: {},
      };
      this.isDefined = false;
      this.ownerFirstName = "";
    },
  },
});
